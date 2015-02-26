<?php
/**
 * Created by PhpStorm.
 * User: avanloock
 * Date: 12.01.15
 * Time: 22:35
 */
namespace Avl\UserBundle\Controller;

use Avl\UserBundle\Entity\Enquiry;
use Avl\UserBundle\Entity\User;
use Avl\UserBundle\Form\Type\EnquiryType;
use Avl\UserBundle\Controller\Controller as BaseController;
use Symfony\Component\HttpFoundation\Request;

/**
 * EnquiryController
 *
 * Class EnquiryController
 *
 * @package Avl\UserBundle\Controller
 */
class EnquiryController extends BaseController
{
    /**
     * View or send the contact formdata
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function enquiryAction(Request $request)
    {
        $enquiry = new Enquiry($this->getUser());
        $form = $this->createForm(new EnquiryType(), $enquiry);
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid() && $this->sendMail($enquiry)) {
                $this->get('session')->getFlashBag()->add('notice', 'enquiry.flash.success');
                return $this->redirect($this->generateUrl('avl_faq_enquiry'));
            } else {
                $this->get('session')->getFlashBag()->add('warning', 'enquiry.flash.error');
            }
        }

        return $this->render(
            'UserBundle:Enquiry:index.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    /**
     * Send email
     *
     * @param  Enquiry $enquiry
     * @return mixed
     */
    private function sendMail($enquiry)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf($this->getEnquiryParameter('subject'), $enquiry->getSubject()))
            ->setFrom($enquiry->getEmail())
            ->setReturnPath($this->getEnquiryParameter('returnpath'))
            ->setTo($this->getEnquiryParameter('to'))
            ->setBody($this->getMailBody($enquiry));

        // If any attachment exists
        if ($enquiry->hasAttachment()) {
            if (null !== $enquiry->getAttachment()) {
                $message->attach(
                    \Swift_Attachment::fromPath($enquiry->getAttachment())
                        ->setFilename(
                            $enquiry->getAttachment()->getClientOriginalName()
                        )
                        ->setContentType(
                            $enquiry->getAttachment()->getClientMimeType()
                        )
                );
            }
        }
        return $this->get('mailer')->send($message);
    }

    /**
     * @param string $parameter
     * @return string
     */
    private function getEnquiryParameter($parameter)
    {
        return $this->container->getParameter('enquiry.email.address.' . $parameter);
    }

    /**
     * @param Enquiry $enquiry
     * @return string
     */
    private function getMailBody($enquiry)
    {
        return $this->renderView(
            'UserBundle:Enquiry:enquiryEmail.txt.twig',
            array(
                'enquiry' => $enquiry
            )
        );
    }
}
