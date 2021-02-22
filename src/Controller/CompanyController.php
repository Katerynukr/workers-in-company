<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Company;

class CompanyController extends AbstractController
{
    /**
     * @Route("/company", name="company_index", methods= {"GET"})
     */
    public function index(Request $r): Response
    {

        //$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $companies = $this->getDoctrine()
        ->getRepository(Company::class);
        
        if($r->query->get('sort_by') == 'sort_by_uab_asc'){
            $companies = $companies->findBy([],['uab' => 'asc']);
        }elseif($r->query->get('sort_by') == 'sort_by_uab_desc'){
            $companies = $companies->findBy([],['uab' => 'desc']);
        }elseif($r->query->get('sort_by') == 'sort_by_name_asc'){
            $companies = $companies->findBy([],['name' => 'asc']);
        }elseif($r->query->get('sort_by') == 'sort_by_name_desc'){
            $companies = $companies->findBy([],['name' => 'desc']);
        }else{
            $companies = $companies->findAll();
        }

        return $this->render('company/index.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'companies' => $companies,
            'sortBy' => $r->query->get('sort_by') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', []),
        ]);
    }


    /**
     * @Route("/company/create", name="company_create", methods= {"GET"})
     */
    public function create(Request $r): Response
    {
        $company_uab =  $r->getSession()->getFlashBag()->get('company_uab', []);
        $company_address =  $r->getSession()->getFlashBag()->get('company_address', []);
        $company_place =  $r->getSession()->getFlashBag()->get('company_place', []);
        $company_name =  $r->getSession()->getFlashBag()->get('company_name', []);

        return $this->render('company/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'company_uab' => $company_uab[0] ?? '',
            'company_address' => $company_address[0] ?? '',
            'company_name' => $company_name[0] ?? '',
            'company_place' => $company_place[0] ?? '',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

    /**
     * @Route("/company/store", name="company_store", methods= {"POST"})
    */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('company_create');
        }

        $company = new Company;
        $company->
        setName((int)$r->request->get('company_name'))->
        setUab($r->request->get('company_uab'))->
        setPlace($r->request->get('company_place'))->
        setAddress((int)$r->request->get('company_address'));

        $errors = $validator->validate($company);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('company_uab', $r->request->get('company_uab'));
            $r->getSession()->getFlashBag()->add('company_name', $r->request->get('company_name'));
            $r->getSession()->getFlashBag()->add('company_address', $r->request->get('company_address'));
            $r->getSession()->getFlashBag()->add('company_place', $r->request->get('company_place'));
            return $this->redirectToRoute('company_create');
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($company);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Company was successfully created');

        return $this->redirectToRoute('company_index');
    }

    /**
     * @Route("/company/edit/{id}", name="company_edit", methods= {"GET"})
    */
    public function edit(Request $r, int $id): Response
    {
        $company = $this->getDoctrine()
        ->getRepository(Company::class)
        ->find($id);
        
        return $this->render('company/edit.html.twig', [
            'company' => $company,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }

    /**
     * @Route("/company/update/{id}", name="company_update", methods= {"POST"})
    */
    public function update(Request $r, int $id, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');

        $company = $this->getDoctrine()
        ->getRepository(Company::class)
        ->find($id);

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('company_edit', ['id'=>$company->getId()]);
        }

        $company->
        setName((int)$r->request->get('company_name'))->
        setUab($r->request->get('company_uab'))->
        setPlace($r->request->get('company_place'))->
        setAddress((int)$r->request->get('company_address'));
        

        $errors = $validator->validate($company);
        if (count($errors) > 0){
            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            return $this->redirectToRoute('company_edit', ['id'=>$company->getId()]);
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->persist($company);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Company was successfully edited');

        return $this->redirectToRoute('company_index');
    }

    /**
     * @Route("/company/delete/{id}", name="company_delete", methods= {"POST"})
    */
    public function delete(Request $r, int $id): Response
    {
        $submittedToken = $r->request->get('token');

        if (!$this->isCsrfTokenValid('check_csrf_hidden', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Bad talken CSRF');
            return $this->redirectToRoute('company_index');
        }

        $company = $this->getDoctrine()
        ->getRepository(Company::class)
        ->find($id);

        if ($company->getCustomers()->count() > 0) {
            $r->getSession()->getFlashBag()->add('errors', 'You cannot deleate the company because it has customers' );
            return $this->redirectToRoute('company_index');
        }

        //creating entity manager sending data to database
        $entityManager = $this->getDoctrine()->getManager();
        //organizing data to be send
        $entityManager->remove($company);
        //wrting
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'Company was successfully deleted');

        return $this->redirectToRoute('company_index');
    }
}
