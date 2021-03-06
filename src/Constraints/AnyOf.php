<?php

namespace League\JsonGuard\Constraints;

use League\JsonGuard\Assert;
use League\JsonGuard\SubSchemaValidatorFactory;
use League\JsonGuard\ValidationError;

class AnyOf implements ContainerInstanceConstraint
{
    const KEYWORD = 'anyOf';

    /**
     * {@inheritdoc}
     */
    public static function validate($data, $parameter, SubSchemaValidatorFactory $validatorFactory, $pointer = null)
    {
        Assert::type($parameter, 'array', self::KEYWORD, $pointer);
        Assert::notEmpty($parameter, self::KEYWORD, $pointer);

        foreach ($parameter as $schema) {
            $validator = $validatorFactory->makeSubSchemaValidator($data, $schema, $pointer);
            if ($validator->passes()) {
                return null;
            }
        }
        return new ValidationError(
            'Failed matching any of the provided schemas.',
            self::KEYWORD,
            $data,
            $pointer,
            ['any_of' => $parameter]
        );
    }
}
