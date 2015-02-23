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
use Symfony\Component\HttpFoundation\Session\Session;
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
     * @var null
     */
    private $session;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * View or send the contact formdata
     *
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function enquiryAction(Request $request)
    {
        $enquiry = new Enquiry();

        // If isset user map the
        // username and email to
        // enquiry-form
        //
        $user = $this->getUser();
        if ($user instanceof User && $request->getMethod() != 'POST') {
            $enquiry->setName($user->getUsername());
            $enquiry->setEmail($user->getEmail());
        }

        // Create form
        $form = $this->createForm(
            new EnquiryType(),
            $enquiry
        );

        // If form was send with POST
        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            // Formfields valid?
            if ($form->isValid()) {

                // If send mail was false
                if (!$this->sendMail($enquiry)) {
                    $this->session->getFlashBag()->add(
                        'warning',
                        'enquiry.flash.error'
                    );
                } else {
                    // Send mail was true, redirect
                    // because we prevent user will
                    // repost the form data if they
                    // refresh the site.
                    $this->session->getFlashBag()->add(
                        'notice',
                        'enquiry.flash.success'
                    );

                    return $this->redirect(
                        $this->generateUrl('avl_faq_enquiry')
                    );
                }
            }
        }

        // Render form
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
    private function sendMail(Enquiry $enquiry)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject(sprintf($this->getEnquiryParameter('subject'), $enquiry->getSubject()))
            ->setFrom($enquiry->getEmail())
            ->setReturnPath($this->getEnquiryParameter('returnpath'))
            ->setTo($this->getEnquiryParameter('to'))
            ->setBody(
                $this->renderView('UserBundle:Enquiry:enquiryEmail.txt.twig',
                    array(
                        'enquiry' => $enquiry
                    )
                )
            );

        // If any attachment exists
        if ($enquiry->hasAttachment()) {

            $message->attach(
                \Swift_Attachment::fromPath(
                    $enquiry->getAttachment()
                )
                    ->setFilename(
                        $enquiry->getAttachment()->getClientOriginalName()
                    )
                    ->setContentType(
                        $enquiry->getAttachment()->getClientMimeType()
                    )
            );
        }
        return $this->get('mailer')->send($message);
    }

    /**
     * @param $parameter
     * @return mixed
     */
    private function getEnquiryParameter($parameter)
    {
        return $this->container->getParameter('enquiry.email.address.' . $parameter);
    }
}
