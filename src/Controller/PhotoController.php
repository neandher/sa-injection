<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use Doctrine\DBAL\DBALException as DBALExceptionAlias;
use Doctrine\ORM\NonUniqueResultException as NonUniqueResultExceptionAlias;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PhotoController extends AbstractController
{
    /**
     * @Route("", name="index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $photoRepository = $this->getDoctrine()->getRepository(Photo::class);
        /** @var Photo[] $photos */
        $photos = $photoRepository->findAllPhotos($request->query->get('s'));

        return $this->render('photo/index.html.twig', [
            'photos' => $photos
        ]);
    }

    /**
     * @Route("/photo/{id}", name="photo_view")
     * @param $id
     * @return Response
     * @throws DBALExceptionAlias
     */
    public function photoView($id)
    {
        $photo = $this->getDoctrine()->getRepository(Photo::class)->findOneById($id);
        return $this->render('photo/photo_view.html.twig', [
            'photo' => $photo
        ]);
    }

    /**
     * @Route("/send-photo", name="send_photo")
     * @param Request $request
     * @return Response
     */
    public function sendPhoto(Request $request)
    {
        $photo = new Photo();
        $photo->setUser($this->getUser());
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('imageFile')->getData();
            $fileName = md5(uniqid()) . '.' . ($file->guessExtension() ? $file->guessExtension() : $file->getClientOriginalExtension());
            try {
                $file->move(
                    $this->getParameter('kernel.project_dir') . '/public/uploads/photos',
                    $fileName
                );
            } catch (FileException $e) {
            }
            $photo->setFile($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($photo);
            $em->flush();

            return $this->redirectToRoute('index');
        }
        return $this->render('photo/send_photo.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
