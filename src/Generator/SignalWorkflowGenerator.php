<?php

declare(strict_types=1);

namespace Spiral\TemporalBridge\Generator;

use Carbon\CarbonInterval;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use Temporal\Activity\ActivityOptions;
use Temporal\Internal\Workflow\ActivityProxy;
use Temporal\Workflow;

final class SignalWorkflowGenerator implements FileGeneratorInterface
{
    public function generate(Context $context, PhpNamespace $namespace): PhpCodePrinter
    {
        $class = new ClassType(
            $context->getClassName()
        );

        $class->addImplement($context->getClassNameWithNamespace('Interface'));

        $class->addProperty('exit')
            ->setType('bool')
            ->setValue(false);

        $method = $class->addMethod($context->getHandlerMethodName())
            ->setPublic()
            ->setReturnType('\Generator');

        $method->addBody(\sprintf(<<<'BODY'
$result = [];

while (true) {
    yield Workflow::await(fn() => $this->exit);
    
    if ($this->exit) {
        return $result;
    }
    
    // Do something ...
    
    $result[] = time();
}
BODY
));

        Utils::generateWorkflowSignalMethods($context->getSignalMethods(), $class);
        Utils::generateWorkflowQueryMethods($context->getQueryMethods(), $class);
        Utils::addParameters($context->getParameters(), $method);

        return new PhpCodePrinter(
            $namespace
                ->add($class)
                ->addUse(ActivityOptions::class)
                ->addUse(CarbonInterval::class)
                ->addUse(Workflow::class)
                ->addUse(ActivityProxy::class),
            $context
        );
    }
}
