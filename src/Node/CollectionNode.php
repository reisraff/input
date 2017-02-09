<?php

namespace Linio\Component\Input\Node;

use Linio\Component\Input\Exception\RequiredFieldException;

class CollectionNode extends BaseNode
{
    public function getValue($field, $value)
    {
        $this->checkConstraints($field, $value);

        $items = [];

        foreach ($value as $collectionValue) {
            if ($this->transformer) {
                $items[] = $this->transformer->transform($collectionValue);
            } else {
                $items[] = $this->instantiator->instantiate($this->type, $collectionValue);
            }
        }

        return $items;
    }

    public function walk($input)
    {
        $result = [];

        if (!$this->hasChildren()) {
            return $input;
        }

        foreach ($input as $inputItem) {
            $itemResult = [];

            foreach ($this->getChildren() as $field => $config) {
                if (!array_key_exists($field, $inputItem)) {
                    if ($config->isRequired()) {
                        throw new RequiredFieldException($field);
                    }

                    if (!$config->hasDefault()) {
                        continue;
                    }

                    $inputItem[$field] = $config->getDefault();
                }

                $itemResult[$field] = $config->getValue($field, $config->walk($inputItem[$field]));
            }

            $result[] = $itemResult;
        }

        return $result;
    }
}
