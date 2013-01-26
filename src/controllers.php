<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints;

// Routes.

// Index.
$app->get(
    '/',
    function () use ($app) {
        return $app['twig']->render('index.html.twig', array());
    }
)->bind('index');

// Contact.
$app->match(
    '/contact',
    function (Request $request) use ($app) {
        $data = array(
            'name' => '',
            'email' => '',
            'message' => ''
        );

        $form = $app['form.factory']->createBuilder('form', $data)
            ->add(
                'name',
                'text',
                array(
                    'constraints' => array(new Constraints\NotBlank()),
                    'required' => true
                )
            )
            ->add(
                'email',
                'email',
                array(
                    'constraints' => array(new Constraints\Email()),
                    'required' => false
                )
            )
            ->add(
                'message',
                'textarea',
                array(
                    'constraints' => array(new Constraints\NotBlank()),
                    'attr' => array(
                        'rows' => 6,
                        'cols' => 40
                    ),
                    'required' => true
                )
            )
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $email = $data['email'] ? $data['email'] : 'Not given';
                $messageBody = "Name: {$data['name']}\nEmail: {$email}\nMessage: {$data['message']}";

                $message = \Swift_Message::newInstance()
                    ->setSubject("{$app['settings']['main_domain']}: Message")
                    ->setFrom(array($app['settings']['contact_email']))
                    ->setTo(array($app['settings']['contact_email']))
                    ->setBody($messageBody);

                $app['mailer']->send($message);

                $app['session']->setFlash('success', 'Thank you for submitting your message.');
            }
        }

        return $app['twig']->render('contact.html.twig', array('form' => $form->createView()));
    }
)->bind('contact');


// Error handling.
$app->error(
    function (\Exception $e, $code) use ($app) {
        $response = null;
        if (!$app['debug']) {
            $page = ($code == 404) ? '404.html.twig' : '500.html.twig';

            $response = new Response($app['twig']->render($page, array('code' => $code)), $code);
        }
        return $response;
    }
);
