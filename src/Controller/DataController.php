<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Data;
use App\Repository\DataRepository;

class DataController extends AbstractController
{
  /**
   * @var integer  200 OK
   */
  protected $statusCode = 200;

  /**
   * @return integer
   */
  public function getStatusCode()
  {
   return $this->statusCode;
  }
  /**
   * @param integer $statusCode
   * @return self
   */
  protected function setStatusCode($statusCode)
  {
    $this->statusCode = $statusCode;

    return $this;
  }
  /**
   * @param Request $request
   * @param EntityManagerInterface $entityManager
   * @param DataRepository $dataRepository
   * @return JsonResponse
   * @throws \Exception
   * @Route("/addUser", name="user_add", methods={"POST"})
   */
  public function addUser(Request $request, EntityManagerInterface $entityManager, DataRepository $dataRepository){

    try{
      $request = $this->transformJsonBody($request);
      if (!$request || !$request->get('email') || !$request->get('password')){
        $errors = "Data user no valid";
        throw new \Exception();
      }
      $email = $request->get('email');
      $user = $dataRepository->findOneByEmail($email);
      if($user) {
        $errors = "Data already exist";
        throw new \Exception();
      }

      $user = new Data();
      $user->setEmail($request->get('email'));
      $user->setPassword($request->get('password'));
      $user->setPublic(1);
      $entityManager->persist($user);
      $entityManager->flush();

      $data = [
       'status'  => $this->getStatusCode(),
       'success' => "User created successfully",
      ];

      return $this->response($data);
    }catch (\Exception $e){
      $errors = $errors ?? "Data user no valid";
      $this->setStatusCode(422);

      return $this->respondWithErrors($errors);
    }
  }

  /**
   * @param Request $request
   * @param DataRepository $dataRepository
   * @return JsonResponse
   * @Route("/authUser", name="user_get", methods={"POST"})
   */
  public function authUser(DataRepository $dataRepository, Request $request){
    $email = $request->get('email');
    $password = $request->get('password');

    $user = $dataRepository->findOneByEmail($email);
    if(!$user->verifyPassword($password)) {
      $this->setStatusCode(404);

      return $this->respondWithErrors('Password is not valid');
    }
    if(!$user){
      $this->setStatusCode(404);

      return $this->respondWithErrors('User not found');
    }

    return $this->respondWithSuccess('User is authorized');
  }

  /**
   *
   * @param array $data
   * @param $status
   * @param array $headers
   * @return JsonResponse
   */
  public function response($data, $status = 200, $headers = [])
  {
   return new JsonResponse($data, $status, $headers);
  }
  /**
   * Sets an error message and returns a JSON response
   *
   * @param string $errors
   * @param $headers
   * @return JsonResponse
   */
  public function respondWithErrors($errors, $headers = [])
  {
    $data = [
      'status' => $this->getStatusCode(),
      'errors' => $errors,
    ];
    return new JsonResponse($data, $this->getStatusCode(), $headers);
  }
   /**
   * Sets an error message and returns a JSON response
   *
   * @param string $success
   * @param $headers
   * @return JsonResponse
   */
  public function respondWithSuccess($success, $headers = [])
  {
    $data = [
      'status' => $this->getStatusCode(),
      'success' => $success,
    ];

    return new JsonResponse($data, $this->getStatusCode(), $headers);
  }
  protected function transformJsonBody(\Symfony\Component\HttpFoundation\Request $request)
  {
    $data = json_decode($request->getContent(), true);

    if ($data == null) {
      return $request;
    }

    $request->request->replace($data);

    return $request;
  }
}
