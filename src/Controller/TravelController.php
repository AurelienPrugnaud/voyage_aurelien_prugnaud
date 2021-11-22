<?php

namespace App\Controller;

use App\Entity\Travel;
use App\Form\TravelType;
use App\Repository\TravelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/travel")
 */
class TravelController extends AbstractController
{
    /**
     * @Route("/", name="travel_index", methods={"GET"})
     */
    public function index(TravelRepository $travelRepository): Response
    {
        return $this->render('travel/index.html.twig', [
            'travel' => $travelRepository->findAll(),
        ]);
    }

    /**
	 * @IsGranted("ROLE_ADMIN")
     * @Route("/new", name="travel_new", methods={"GET", "POST"})
     */
    public function new(Request $request,ValidatorInterface $validator, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $travel = new Travel();
        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
			$errors = $validator->validate($form);
			if (count($errors) > 0) {
				return $this->renderForm('travel/new.html.twig', [
					'errors' => $errors,
					'travel' => $travel,
					'form' => $form
				]);

			} else {

				$pdf = $form->get('pdf')->getData();
				if ($pdf) {
					$originalFilename = pathinfo($pdf->getClientOriginalName(), PATHINFO_FILENAME);
					// this is needed to safely include the file name as part of the URL
					$safeFilename = $slugger->slug($originalFilename);
					$newFilename = $safeFilename . '-' . uniqid() . '.' . $pdf->guessExtension();

					// Move the file to the directory where brochures are stored
					try {
						$pdf->move(
							$this->getParameter('pdf_directory'),
							$newFilename
						);
					} catch (FileException $e) {
						// ... handle exception if something happens during file upload
					}

					// updates the 'brochureFilename' property to store the PDF file name
					// instead of its contents
					$travel->setPdf($newFilename);
				}

				$imgFile1 = $form->get('image1')->getData();
				if ($imgFile1) {
					$originalFilename = pathinfo($imgFile1->getClientOriginalName(), PATHINFO_FILENAME);
					$safeFilename = $slugger->slug($originalFilename);
					$newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile1->guessExtension();
					try {
						$imgFile1->move(
							$this->getParameter('img_directory'),
							$newFilename
						);
					} catch (FileException $e) {
					}
					$travel->setImage1($newFilename);
				}

				$imgFile2 = $form->get('image2')->getData();
				if ($imgFile2) {
					$originalFilename = pathinfo($imgFile2->getClientOriginalName(), PATHINFO_FILENAME);
					$safeFilename = $slugger->slug($originalFilename);
					$newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile2->guessExtension();
					try {
						$imgFile2->move(
							$this->getParameter('img_directory'),
							$newFilename
						);
					} catch (FileException $e) {
					}
					$travel->setImage2($newFilename);
				}

				$imgFile3 = $form->get('image3')->getData();
				if ($imgFile3) {
					$originalFilename = pathinfo($imgFile3->getClientOriginalName(), PATHINFO_FILENAME);
					$safeFilename = $slugger->slug($originalFilename);
					$newFilename = $safeFilename . '-' . uniqid() . '.' . $imgFile3->guessExtension();
					try {
						$imgFile3->move(
							$this->getParameter('img_directory'),
							$newFilename
						);
					} catch (FileException $e) {
					}
					$travel->setImage3($newFilename);
				}

				$entityManager->persist($travel);
				$entityManager->flush();

				return $this->redirectToRoute('travel_index', [], Response::HTTP_SEE_OTHER);
			}
		}

		return $this->renderForm('travel/new.html.twig', [
			'travel' => $travel,
			'form' => $form,
		]);
    }

    /**
     * @Route("/{id}", name="travel_show", methods={"GET"})
     */
    public function show(Travel $travel): Response
    {
        return $this->render('travel/show.html.twig', [
            'travel' => $travel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="travel_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Travel $travel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TravelType::class, $travel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('travel_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('travel/edit.html.twig', [
            'travel' => $travel,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="travel_delete", methods={"POST"})
     */
    public function delete(Request $request, Travel $travel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$travel->getId(), $request->request->get('_token'))) {
            $entityManager->remove($travel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('travel_index', [], Response::HTTP_SEE_OTHER);
    }
}
