<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Company;
use App\Entity\Customer;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer", name="customer_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $companies =  $this->getDoctrine()
        ->getRepository(Company::class)
        ->findAll();
        
        $customers = $this->getDoctrine()
        ->getRepository(Customer::class);

       
        if($r->query->get('filter') == 0 ){
            $customers= $customers->findAll();
            
        }else{
            $customers = $customers->findBy(['company_id'=> $r->query->get('filter')], ['surname' => 'asc']);
        }

        return $this->render('customer/index.html.twig', [
            'customers'=>$customers,
            'companies' => $companies,
            'companyID'=>$r->query->get('filter') ?? 0,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

    /**
     * @Route("/customer/create", name="customer_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {

        $companies =  $this->getDoctrine()
        ->getRepository(Company::class)
        ->findAll();

        if(empty($companies)){
            $r->getSession()->getFlashBag()->add('errors', 'You cant create a customer because there are no companies');
            $r->getSession()->getFlashBag()->add('errors', 'Create a new company');
            return $this->redirectToRoute('company_create');
        }
        
        $customer_name= $r->getSession()->getFlashBag()->get('customer_name', []);
        $customer_surname = $r->getSession()->getFlashBag()->get('customer_surname', []);
        $customer_phone = $r->getSession()->getFlashBag()->get('customer_phone', []);
        $customer_email = $r->getSession()->getFlashBag()->get('customer_email', []);
        $customer_comment = $r->getSession()->getFlashBag()->get('customer_comment', []);
        $customer_company = $r->getSession()->getFlashBag()->get('customer_company_id');
       
        
        return $this->render('customer/create.html.twig', [
            'customer_name' => $customer_name[0] ?? '',
            'customer_surname' => $customer_surname[0] ?? '',
            'customer_phone' => $customer_phone[0] ?? '',
            'customer_email' => $customer_email[0] ?? '',
            'customer_comment' => $customer_comment[0] ?? '',
            'customer_company' => $customer_company,
            'companies' => $companies,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', []),
        ]);
    }

     /**
     * @Route("/customer/create", name="customer_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('customer_create');
        }

        $company =  $this->getDoctrine()
        ->getRepository(Company::class)
        ->find($r->request->get('customer_company_id'));

        if($company == null){
            $r->getSession()->getFlashBag()->add('errors', 'Choose company from the list');
        }

        $customer = new Customer;

        $customer->
        setName($r->request->get('customer_name'))->
        setSurname($r->request->get('customer_surname'))->
        setPhone($r->request->get('customer_phone'))->
        setEmail($r->request->get('customer_email'))->
        setComment($r->request->get('customer_comment'))->
        setCompany($company);

        $errors = $validator->validate($customer);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('note_title', $r->request->get('note_title'));
            $r->getSession()->getFlashBag()->add('note_priority', $r->request->get('note_priority'));
            $r->getSession()->getFlashBag()->add('note_note', $r->request->get('note_note'));
            $r->getSession()->getFlashBag()->add('note_status_id', $r->request->get('note_status_id'));
            return $this->redirectToRoute('customer_create');
        }
        if(null === $company) {
            return $this->redirectToRoute('customer_create');
        }


        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($customer);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Customer was successfully created');

        return $this->redirectToRoute('customer_index');
    }

    /**
     * @Route("/customer/edit/{id}", name="customer_edit", methods= {"GET"})
     */
    public function edit(Request $r, int $id): Response
    {
        $customer = $this->getDoctrine()
        ->getRepository(Customer::class)
        ->find($id);

        $companies =  $this->getDoctrine()
        ->getRepository(Company::class)
        ->findAll();
        
        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'companies' => $companies,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', []),
        ]);
    }

    /**
     * @Route("/customer/update/{id}", name="customer_update", methods= {"POST"})
     */
    public function update(Request $r, int $id, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');


        $customer = $this->getDoctrine()
        ->getRepository(Customer::class)
        ->find($id);

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('customer_edit' , ['id'=>$customer->getId()]);
        }
        
        $company =  $this->getDoctrine()
        ->getRepository(Company::class)
        ->find($r->request->get('customer_company_id'));

        $customer->
        setName($r->request->get('customer_name'))->
        setSurname($r->request->get('customer_surname'))->
        setPhone($r->request->get('customer_phone'))->
        setEmail($r->request->get('customer_email'))->
        setComment($r->request->get('customer_comment'))->
        setCompany($company);

        $errors = $validator->validate($customer);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('note_edit', ['id'=>$customer->getId()] );
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($customer);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Customer was successfully edited');

        return $this->redirectToRoute('customer_index');
    }

    /**
     * @Route("/customer/delete/{id}", name="customer_delete", methods= {"POST"})
     */
    public function delete(Request $r, int $id): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('customer_index');
        }

        $customer = $this->getDoctrine()
        ->getRepository(Customer::class)
        ->find($id);

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->remove($customer);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Customer was successfully deleted');

        return $this->redirectToRoute('customer_index');
    }
}
