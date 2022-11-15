<?php

namespace App\Controller;

use App\Entity\Prof;
use App\Repository\ProfRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfController extends AbstractController
{
    #[Route('api/profs', name: 'prof', methods: ['GET'])]
    public function getProfList(ProfRepository $profRepository, SerializerInterface $serializer): JsonResponse
    {
        $profList= $profRepository->findAll();
        $jsonProfList= $serializer->serialize($profList, 'json', ['groups'=>'getProfs']);
        return new JsonResponse($jsonProfList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/profs/{id}', name: 'detailProf', methods:['GET'])]
    public function getDetailProf(Prof $prof, SerializerInterface $serializer, ProfRepository $profRepository): JsonResponse {
         {
            $jsonProf= $serializer->serialize($prof, 'json', ['groups'=>'getProfs']);
            return new JsonResponse($jsonProf, Response::HTTP_OK,['accept'=> 'json'], true);
        }
        
    }

    #[Route('/api/profs/{id}', name: 'deleteProf', methods:['DELETE'])]
    public function deleteProf(Prof $prof, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($prof);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/profs', name: 'createProf', methods:['POST'])]
        public function createProf(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse {
            $prof=$serializer->deserialize($request->getContent(),Prof::class, 'json');

            $classe=$serializer->deserialize($request->getContent(),Prof::class, 'json');
            
            // on verifie les erreurs
            $errors=$validator->validate($prof);

            if ($errors->count()>0) {
                return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                
            }

            $em->persist($prof);
            $em->flush();

            $jsonProf= $serializer->serialize($prof, 'json', ['groups'=>'getProfs']);

            $location=$urlGenerator->generate('detailProf', ['id'=>$prof->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            return new JsonResponse($jsonProf, Response::HTTP_CREATED, ["location"=>$location], true);
        }

        #[Route('/api/profs/{id}', name: 'updateProf', methods:['PUT'])]
        public function updateProf(Request $request, SerializerInterface $serializer, Prof $currentProf, EntityManagerInterface $em, ProfRepository $profRepository): JsonResponse {
            $updatedProf=$serializer->deserialize($request->getContent(), Prof::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE=>$currentProf]);
            
            $em->persist(($updatedProf));
            $em->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }
}
