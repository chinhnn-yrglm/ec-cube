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

namespace Eccube\Controller;

use Eccube\Entity\Customer;
use Eccube\Entity\Inquiry;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Front\InquiryType;
use Eccube\Repository\PageRepository;
use Eccube\Service\InquiryService;
use Eccube\Service\MailService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InquiryController extends AbstractController
{
    /**
     * @var MailService
     */
    protected $mailService;

    /**
     * @var InquiryService
     */
    private $inquiryService;

    /**
     * @var PageRepository
     */
    private $pageRepository;

    /**
     * InquiryController constructor.
     *
     * @param MailService $mailService
     * @param InquiryService $inquiryService
     */
    public function __construct(
        MailService $mailService,
        InquiryService $inquiryService,
        PageRepository $pageRepository)
    {
        $this->mailService = $mailService;
        $this->inquiryService = $inquiryService;
        $this->pageRepository = $pageRepository;
    }

    /**
     * Inquiry
     *
     * @Route("/inquiry", name="inquiry", methods={"GET", "POST"})
     * @Route("/inquiry", name="inquiry_confirm", methods={"GET", "POST"})
     *
     * @Template("Inquiry/index.twig")
     */
    public function index(Request $request)
    {
        $Inquiry = new Inquiry();

        if ($this->isGranted('ROLE_USER')) {
            /** @var Customer $user */
            $user = $this->getUser();
            $Inquiry->setName01($user->getName01());
            $Inquiry->setName02($user->getName02());
            $Inquiry->setKana01($user->getKana01());
            $Inquiry->setKana02($user->getKana02());
            $Inquiry->setPhoneNumber($user->getPhoneNumber());
            $Inquiry->setEmail($user->getEmail());
        }

        $builder = $this->formFactory->createBuilder(InquiryType::class, $Inquiry);

        // FRONT_INQUIRY_INDEX_INITIALIZE
        $event = new EventArgs(
            [
                'builder' => $builder,
                'Inquiry' => $Inquiry,
            ],
            $request
        );
        $this->eventDispatcher->dispatch($event, EccubeEvents::FRONT_INQUIRY_INDEX_INITIALIZE);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            switch ($request->get('mode')) {
                case 'confirm':
                    return $this->render('Inquiry/confirm.twig', [
                        'form' => $form->createView(),
                        'Page' => $this->pageRepository->getPageByRoute('inquiry_confirm'),
                    ]);

                case 'complete':
                    $data = $form->getData();

                    $event = new EventArgs(
                        [
                            'form' => $form,
                            'data' => $data,
                        ],
                        $request
                    );
                    $this->eventDispatcher->dispatch($event, EccubeEvents::FRONT_INQUIRY_INDEX_COMPLETE);

                    // Save data
                    $this->inquiryService->save($Inquiry);

                    // Send mail
                    $data->postal_code = false;
                    $data->pref = false;
                    $data->addr01 = null;
                    $data->addr02 = null;
                    $data->contents = $Inquiry->getContent();
                    $this->mailService->sendContactMail($data);

                    return $this->redirect($this->generateUrl('inquiry_complete'));
            }
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * Complete.
     *
     * @Route("/inquiry/complete", name="inquiry_complete", methods={"GET"})
     *
     * @Template("Inquiry/complete.twig")
     */
    public function complete()
    {
        return [];
    }
}
