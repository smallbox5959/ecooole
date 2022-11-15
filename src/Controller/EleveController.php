<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Repository\ProfRepository;
use App\Repository\EleveRepository;
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

class EleveController extends AbstractController
{
    #[Route('api/Eleves', name: 'Eleve', methods: ['GET'])]
    public function getEleveList(EleveRepository $eleveRepository, SerializerInterface $serializer): JsonResponse
    {
        $eleveList= $eleveRepository->findAll();
        $jsonEleveList= $serializer->serialize($eleveList, 'json', ['groups' =>'getEleves']);
        return new JsonResponse($jsonEleveList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/Eleves/{id}', name: 'detailEleve', methods:['GET'])]
    public function getDetailEleve(Eleve $eleve, SerializerInterface $serializer, EleveRepository $eleveRepository): JsonResponse {
         {
            $jsonEleve= $serializer->serialize($eleve, 'json', ['groups'=>'getEleves']);
            return new JsonResponse($jsonEleve, Response::HTTP_OK,['accept'=> 'json'], true);
        }
        
    }

    #[Route('/api/Eleves/{id}', name: 'deleteEleve', methods:['DELETE'])]
    public function deleteEleve(Eleve $eleve, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($eleve);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/Eleves', name: 'createEleve', methods:['POST'])]
        public function createEleve(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ProfRepository $profRepository, ValidatorInterface $validator): JsonResponse {
            $eleve=$serializer->deserialize($request->getContent(),Eleve::class, 'json');

            // on verifie les erreurs
            $errors=$validator->validate($eleve);

            if ($errors->count()>0) {
                return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                
            }


            $content=$request->toArray();
            $idProf=$content['idProf'] ?? -1;
            $eleve->setProf($profRepository->find($idProf));

            $em->persist($eleve);
            $em->flush();

            $jsonEleve= $serializer->serialize($eleve, 'json', ['groups'=>'getEleves']);

            $location=$urlGenerator->generate('detailEleve', ['id'=>$eleve->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            return new JsonResponse($jsonEleve, Response::HTTP_CREATED, ["location"=>$location], true);
        }

    #[Route('/api/Eleves/{id}', name: 'updateEleve', methods:['PUT'])]    
        public function updateEleve(Request $request, SerializerInterface $serializer, Eleve $currentEleve, EntityManagerInterface $em, ProfRepository $profRepository): JsonResponse {
            $updatedEleve=$serializer->deserialize($request->getContent(), Eleve::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE=>$currentEleve]);
            $content=$request->toArray();
            $idProf=$content['idProf']?? -1;
            $updatedEleve->setProf($profRepository->find($idProf));
            $em->persist(($updatedEleve));
            $em->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }


}
