<?php

declare(strict_types=1);

namespace Spiral\TemporalBridge\Generator;

use Nette\PhpGenerator\PhpNamespace;
use Spiral\TemporalBridge\Workflow\RunningWorkflow;

class HandlerInterfaceGenerator implements FileGeneratorInterface
{
    public function generate(Context $context, PhpNamespace $namespace): PhpCodePrinter
    {
        $class = \Nette\PhpGenerator\InterfaceType::from($context->getClass());

        $class->addMember($handler = $context->getHandlerMethod());
        $handler->setReturnType(RunningWorkflow::class);

        return new PhpCodePrinter(
            $namespace
                ->add($class)
                ->addUse(RunningWorkflow::class),
            $context
        );
    }
}
