<?php

namespace App\Controller;

use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomerController{
private $CustomerRepository;

    public function __construct(CustomerRepository $CustomerRepository)
    {
        $this->CustomerRepository = $CustomerRepository;
    }

	 /**
     * @Route("/customers/add", name="add_customer", methods={"POST"})
     */
    public function add(Request $request): JsonResponse{
		
        $data = json_decode($request->getContent(), true);

        $FirstName = $data['FirstName'];
        $LastName = $data['LastName'];
        $PhoneNumber = $data['PhoneNumber'];
        $Email = $data['Email'];


        if (empty($FirstName) || empty($LastName) || empty($PhoneNumber) || empty($Email)) {
            throw new NotFoundHttpException("Fields Shouldn't be empty!");
        }

        $this->CustomerRepository->addCustomer($FirstName, $LastName, $PhoneNumber, $Email);
        return new JsonResponse(['status' => 'Customer added!'], Response::HTTP_CREATED);
    }
	
	/**
	 * @Route("/customers/get", name="get_customers", methods={"GET"})
	 */
	public function getAll(): JsonResponse{
		$Customers = $this->CustomerRepository->findAll();
		//$data = [];

		foreach ($Customers as $Customer) {
			$data[] = [
				'id' => $Customer->getId(),
				'FirstName' => $Customer->getFirstName(),
				'LastName' => $Customer->getLastName(),
				'PhoneNumber' => $Customer->getPhoneNumber(),
				'Email' => $Customer->getEmail(),

			];
		}

		return new JsonResponse($data, Response::HTTP_OK);
	}
	
	/**
	 * @Route("/customers/get/{id}", name="get_one_customer", methods={"GET"})
	 */
	public function getOne($id): JsonResponse{
		$Customer = $this->CustomerRepository->findOneBy(['id' => $id]);
		$data = [
			'id' => $Customer->getId(),
			'FirstName' => $Customer->getFirstName(),
			'LastName' => $Customer->getLastName(),
			'PhoneNumber' => $Customer->getPhoneNumber(),
			'Email' => $Customer->getEmail(),

		];

		return new JsonResponse($data, Response::HTTP_OK);
	}
	
	/**
	 * @Route("/customers/update/{id}", name="update_customer", methods={"PUT"})
	 */
	public function update($id, Request $request): JsonResponse{
		$Customer = $this->CustomerRepository->findOneBy(['id' => $id]);
		$data = json_decode($request->getContent(), true);

		
        if (!empty($data['FirstName'])) $Customer->setFirstName($data['FirstName']); 
        if (!empty($data['LastName'])) $Customer->setLastName($data['LastName']); 
        if (!empty($data['PhoneNumber'])) $Customer->setPhoneNumber($data['PhoneNumber']); 
        if (!empty($data['Email'])) $Customer->setEmail($data['Email']); 

        $this->CustomerRepository->updateCustomer($Customer);
        return new JsonResponse(['status' => 'Customer updated!'], Response::HTTP_OK);	
		}
		
		/**
		 * @Route("/customers/delete/{id}", name="delete_customer", methods={"DELETE"})
		 */
		public function deleteCustomer($id): JsonResponse{
			$Customer = $this->CustomerRepository->findOneBy(['id' => $id]);
			$this->CustomerRepository->deleteCustomer($Customer);
			return new JsonResponse(['status' => 'Customer deleted'], Response::HTTP_OK);
		}
}



