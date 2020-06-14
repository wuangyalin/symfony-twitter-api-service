<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Service\TwitterService;
use AppBundle\Service\FormatService;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //build seach form
        $form = $this->createFormBuilder()
        ->add('keywords', TextType::class, array(
            'label' => 'Search Twitte',
            'required' => true,
            'attr' => [
                "value" => 'Sydney',
                "id" => "keywords",
                "placeholder" => "Search Twiites",
                "class" => 'form-control',
            ],
        ))
        ->add('search', SubmitType::class, [
            'label' => 'Search',
            'attr' => [
                "id" => "submit_search",
                "class" => "btn btn-primary mb-2"
            ]
        ])
        ->getForm();

        $form->handleRequest($request);
        
        //handle the form search submitted
        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                return new JsonResponse(array(
                    'status' => 400,
                    'message' => 'error',
                    'data' => $this->getErrorMessages($form),
                ));
            } else {
                $formdata = $form->getData();
                if(isset($formdata['keywords'])){
                    $result = $this->get(TwitterService::class)->twitteResponse($formdata['keywords'], '');
                    if($result === false){
                        return new JsonResponse(array(
                            'status' => 400,
                            'message' => 'error',
                            'data' => '<h3>Could not authenticate you.</h3>',
                        ));
                    }else if(empty($result)){
                        return new JsonResponse(array(
                            'status' => 400,
                            'message' => 'error',
                            'data' => '<h3>No Results.</h3>',
                        ));
                    }
                    $data = $this->get(FormatService::class)->formatTwittes($result);
                    return new JsonResponse(array(
                        'status' => 200,
                        'message' => 'Success',
                        'data' => $data,
                    ));
                }
                return new JsonResponse(array(
                    'status' => 400,
                    'message' => 'error',
                    'data' => 'Something went wrong',
                ));
            }
        }

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    /**
     * Featch the error message returned from form
     */
    protected function getErrorMessages(Form $form) 
    {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}
