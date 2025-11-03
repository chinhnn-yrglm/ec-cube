<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Inquiry;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\InquiryRepository;

class InquiryService
{
    /**
     * @var BaseInfoRepository
     */
    protected $inquiryRepository;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * InquiryService constructor.
     *
     * @param InquiryRepository $inquiryRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(InquiryRepository $inquiryRepository, EntityManagerInterface $entityManager)
    {
        $this->inquiryRepository = $inquiryRepository;
        $this->entityManager = $entityManager;
    }

    public function save(Inquiry $inquiry) {
        $this->entityManager->persist($inquiry);
        $this->entityManager->flush();
    }
}
