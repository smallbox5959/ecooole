<?php

namespace App\Controller;

use App\Entity\Classe;
use App\Repository\ProfRepository;
use App\Repository\ClasseRepository;
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

class ClasseController extends AbstractController
{
    #[Route('/api/Classe', name: 'app_Classe', methods: ['GET'] )]
    public function getClasseList(ClasseRepository $ClasseRepository, SerializerInterface $serializer): JsonResponse
    {
        $classeList= $ClasseRepository->findAll();
        $jsonClasseList= $serializer->serialize($classeList, 'json', ['groups' =>'getClasses']);
        return new JsonResponse($jsonClasseList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/Classe/{id}', name: 'detailClasse', methods:['GET'])]
    public function getDetailClasse(Classe $classe, SerializerInterface $serializer, ClasseRepository $ClasseRepository): JsonResponse {
         
            $jsonClasse= $serializer->serialize($classe, 'json', ['groups'=>'getClasses']);
            return new JsonResponse($jsonClasse, Response::HTTP_OK,['accept'=> 'json'], true);    
    }

    #[Route('/api/Classe/{id}', name: 'deleteClasse', methods:['DELETE'])]
    public function deleteClasse(Classe $classe, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($classe);
        $em->flush();
        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/Classe', name: 'createClasse', methods:['POST'])]
        public function createClasse(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, ProfRepository $profRepository, ValidatorInterface $validator): JsonResponse {
            
            $classe=$serializer->deserialize($request->getContent(),Classe::class, 'json');
            
            // on verifie les erreurs
            $errors=$validator->validate($classe);

            if ($errors->count()>0) {
                return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
                
            }

            $content=$request->toArray();
            $idProf=$content['idProf'] ?? -1;
            $classe->setProf($profRepository->find($idProf));

            $em->persist($classe);
            $em->flush();

            $jsonClasse= $serializer->serialize($classe, 'json', ['groups'=>'getClasses']);

            $location=$urlGenerator->generate('detailClasse', ['id'=>$classe->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

            return new JsonResponse($jsonClasse, Response::HTTP_CREATED, ["location"=>$location], true);
        }

        #[Route('/api/Classe/{id}', name: 'updateClasse', methods:['PUT'])]
        public function updateClasse(Request $request, SerializerInterface $serializer, Classe $currentClasse, EntityManagerInterface $em, ProfRepository $profRepository): JsonResponse {
            
            $updatedClasse=$serializer->deserialize($request->getContent(), Classe::class,'json', [AbstractNormalizer::OBJECT_TO_POPULATE=>$currentClasse]);
            
            $content=$request->toArray();
            $idProf=$content['idProf']?? -1;
            $updatedClasse->setProf($profRepository->find($idProf));
            
            $em->persist(($updatedClasse));
            $em->flush();
            return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
        }
}
