<?php

namespace HeidiAlpen\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Bundle\FrameworkBundle\Command\CacheClearCommand;

use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    /*public function localeAction($route = 'heidi_alpen_homepage', $parameters = array())
    {
        echo $this->getRequest()->getPreferredLanguage(array('en', 'de', 'jp', 'ru'));
        $this->getRequest()->setLocale($this->getRequest()->getPreferredLanguage(array('en', 'de', 'jp', 'ru')));
        //return $this->redirect($this->generateUrl($route, $parameters));
    }*/

    public function indexAction()
    {
        return $this->render('HeidiAlpenSiteBundle:Default:index.html.twig');
    }
	public function theStoryAction()
    {
        return $this->render('HeidiAlpenSiteBundle:Default:the-story.html.twig');
    }

    public function theExpertiseAction()
    {
        return $this->render('HeidiAlpenSiteBundle:Default:the-expertise.html.twig');
    }
	
    public function theCheesesAction()
    {
        return $this->render('HeidiAlpenSiteBundle:Default:the-cheeses.html.twig');
    }
    
    public function theCheesesCheeseAction($cheese)
    {
        return $this->render('HeidiAlpenSiteBundle:Default:the-cheeses.html.twig', array(
            'selectedCheese' => $cheese,
            'title' => $this->get('translator')->trans('theCheeses.' . $cheese . '.nom'),
            'description' => $this->get('translator')->trans('theCheeses.' . $cheese . '.seo.description')
        ));
    }
    public function theCheesesCheeseAjaxAction($cheese)
    {
        return $this->render('HeidiAlpenSiteBundle:Default:cheeses/' . $cheese . '.html.twig');
    }

    public function receiptsAction()
    {
        $receipts = [
            array(
                'slug' => 'risotto',
                'img' => '9171.jpg',
                'nom' => 'Risotto'
            ),
            array(
                'slug' => 'salade-du-jardin',
                'img' => '8905.jpg',
                'nom' => 'Salade du jardin'
            ),
            array(
                'slug' => 'gateau-au-fromage',
                'img' => '8957.jpg',
                'nom' => 'Gâteau au fromage'
            ),
            array(
                'slug' => 'pommes-de-terre-au-fromage',
                'img' => '9106.jpg',
                'nom' => 'Pommes de terre au fromage'
            ),
            array(
                'slug' => 'soupe-de-courge',
                'img' => '9088.jpg',
                'nom' => 'Soupe à la courge'
            )
        ];
        shuffle($receipts);
        return $this->render('HeidiAlpenSiteBundle:Default:receipts.html.twig', array('receipts' => $receipts));
    }
    public function receiptAction($receipt)
    {
        return $this->render('HeidiAlpenSiteBundle:Default:receipt.html.twig', array('receipt' => $receipt));
    }

    public function mailAction($template)
    {
        return $this->render('HeidiAlpenSiteBundle:Mail:' . $template . '.html.twig');
    }

    public function contactAction()
    {
        $formBuilder = $this->createFormBuilder();
        $formBuilder
            ->add('salutation', 'choice', array('constraints' => array(new Assert\NotBlank(array('message' => $this->get('translator')->trans('form.required.salutation'))), new Assert\Range(array('min' => 1, 'max' => 3, 'minMessage' => $this->get('translator')->trans('form.required.salutation'), 'maxMessage' => $this->get('translator')->trans('form.required.salutation')))), 'label' => 'form.contact.salutation', 'required' => true, 'empty_data' => null, 'empty_value' => 'form.contact.salutation', 'choices' => array(1 => 'form.contact.choice.mme', 2 => 'form.contact.choice.mlle', 3 => 'form.contact.choice.m'), 'translation_domain' => 'messages'))
            ->add('firstname', 'text', array('constraints' => array(new Assert\NotBlank(array('message' => $this->get('translator')->trans('form.required.firstname'))), new Assert\Length(array('min' => 2))), 'label' => 'form.contact.firstname', 'required' => true, 'attr' => array('placeholder' => 'form.contact.firstname') , 'translation_domain' => 'messages'))
            ->add('lastname', 'text', array('constraints' => array(new Assert\NotBlank(array('message' => $this->get('translator')->trans('form.required.lastname'))), new Assert\Length(array('min' => 2))), 'label' => 'form.contact.lastname', 'required' => true, 'attr' => array('placeholder' => 'form.contact.lastname'), 'translation_domain' => 'messages'))
            ->add('email', 'email', array('constraints' => array(new Assert\NotBlank(array('message' => $this->get('translator')->trans('form.required.email'))), new Assert\Email()), 'label' => 'form.contact.email', 'required' => true, 'attr' => array('placeholder' => 'form.contact.email'), 'translation_domain' => 'messages'))
            ->add('country', 'country', array('constraints' => array(new Assert\NotBlank(array('message' => $this->get('translator')->trans('form.required.country'))), new Assert\Country(array('message' => $this->get('translator')->trans('form.required.country')))), 'label' => 'form.contact.country', 'empty_data' => null, 'empty_value' => 'form.contact.country', 'required' => true, 'translation_domain' => 'messages'))
            ->add('message', 'textarea', array('constraints' => array(new Assert\NotBlank(array('message'=>$this->get('translator')->trans('form.required.message'))), new Assert\Length(array('min' => 2))), 'label' => 'form.contact.message', 'required' => true, 'attr' => array('placeholder' => 'form.contact.message'), 'translation_domain' => 'messages'))
            
            //->add('captcha', 'genemu_recaptcha')
            ->add('submit', 'submit', array('label' => 'form.submit'));
        $form = $formBuilder->getForm();
        
        $request = $this->get('request');
        if($request->getMethod() == 'POST'){
            $form->bind($request);
            
            if ($form->isValid()) {

                $lang = $this->getRequest()->getPreferredLanguage(array('en', 'fr', 'de', 'it', 'es', 'jp', 'cn'));
                
                $em = $this->getDoctrine()->getManager();
                $data = $form->getData();
                /*
                $messageToSend = \Swift_Message::newInstance()
                    ->setSubject('HEIDI Alpen | Contact')
                    ->setFrom(array($this->container->getParameter('mailer_address') => $this->container->getParameter('mailer_name')))
                    ->setTo($this->container->getParameter('mailer_contact_address'))
                    ->setBody($this->renderView('HeidiAlpenSiteBundle:Mail:contact-admin.html.twig', array('data' => $data)), 'text/html');
                */
                // Always set content-type when sending HTML email
                $to = $this->container->getParameter('mailer_contact_address');
                $subject = 'HEIDI Alpen | Contact';
                $message = $this->renderView('HeidiAlpenSiteBundle:Mail:contact-admin.html.twig', array('data' => $data));
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: ' . $this->container->getParameter('mailer_name') . '<' . $this->container->getParameter('mailer_address') . '>' . "\r\n";

                mail($to,$subject,$message,$headers);
                /*
                $this->get('mailer')->send($messageToSend);
                
                $messageToSend2 = \Swift_Message::newInstance()
                    ->setSubject($this->get('translator')->trans('mail.contact.subject'))
                    ->setFrom(array($this->container->getParameter('mailer_contact_address') => $this->container->getParameter('mailer_name')))
                    ->setTo($data['email'])
                    ->setBody($this->renderView('HeidiAlpenSiteBundle:Mail:contact.html.twig'), 'text/html');
                
                $this->get('mailer')->send($messageToSend2);
                */

                $to = $data['email'];
                $subject = 'HEIDI Alpen | Contact';
                $message = $this->renderView('HeidiAlpenSiteBundle:Mail:contact.html.twig');
                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: ' . $this->container->getParameter('mailer_name') . '<' . $this->container->getParameter('mailer_address') . '>' . "\r\n";

                mail($to,$subject,$message,$headers);

                $this->get('session')->getFlashBag()->add('notice', $this->get('translator')->trans('mail.contact.flash.success'));
                $form = $formBuilder->getForm();
                /*
                
                -- log message into dashboard --

                $message = new \HeidiAlpen\SiteBundle\Entity\ContactMessage();
                $message->setDate(new \DateTime());
                $message->setSite($this->getRequest()->getSchemeAndHttpHost());
                $message->setSubject($data['subject']);
                $message->setMessage($data['message']);
                $message->setUser($user);

                $em->persist($message);
                $em->flush();
                */
                return $this->render('HeidiAlpenSiteBundle:Default:contact.html.twig', array('form' => $form->createView(), 'submitted' => true));
            }
        }
        
        return $this->render('HeidiAlpenSiteBundle:Default:contact.html.twig', array('form' => $form->createView(), 'submitted' => false));
    }

    public function filesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $medias = $this->getDoctrine()->getRepository('ApplicationSonataMediaBundle:Media')->findAll();

        return $this->render('HeidiAlpenSiteBundle:Default:files.html.twig', array('medias' => $medias) );
    }

}
