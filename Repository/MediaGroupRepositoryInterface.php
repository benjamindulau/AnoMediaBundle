<?php

namespace Ano\Bundle\MediaBundle\Repository;

use Ano\Bundle\MediaBundle\Model\MediaGroupInterface;

interface MediaGroupRepositoryInterface
{
    public function save(MediaGroupInterface $mediaGroup);
}