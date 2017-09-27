<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Insect;
use AppBundle\Form\InsectEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        //on test si une personne est authentifier, si oui on la dirige vers la route "contact"
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        if (in_array("ROLE_USER", $roles)) {
            return $this->redirectToRoute("contact", ["id" => $user->getId()]);
        }

        // sinon affichage simple de la vue de l'index
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')) . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/contact/{id}", name="contact")
     * @param Insect $insect
     * @return Response
     */
    public function contactAction(Insect $insect)
    {


        //on récupère dans la base de données les données de la table 'insect' grâce à l'entité insect
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Insect");

        // on récupère tout les utilisateurs de l'entité Insect
        $listUsers = $repository->findAll();
        // on récupère l'utilisateur connecté et sa liste d'amis via la méthode créée getAmiList()
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        // sécurité, si on est connecté (role "ROLE_USER")
        if (in_array("ROLE_USER", $roles)) {
            $mesAmis = $user->getAmiList();
            // on tri les users ami et les non-amis
            $UsersPasAmi = array_diff($listUsers, $mesAmis);
        } else {
            // sinon on est redirigé vers la route homepage
            return $this->redirectToRoute("homepage");
        }

        //on retourne une vue avec des paramètres que l'on pourra réutiliser
        return $this->render('default/contact.html.twig', [
            "listUsersPasAmi" => $UsersPasAmi,
            "listAmis" => $insect->getAmiList()
        ]);
    }


    /**
     * @Route("/modifCompte/{id}" ,  name="modifCompte")
     * @param Request $request
     * @param Insect $insect
     * @return Response
     */
    public function modifCompteAction(Request $request, Insect $insect)
    {

        //on récupère dans la base de données les données de la table 'insect' grâce à l'entité insect
        $repository = $this->getDoctrine()
            ->getRepository("AppBundle:Insect");
        // on récupère tout les utilisateurs
        $listUsers = $repository->findAll();

        //sécurité de l'opération
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        $userId = isset($user) ? $user->getId() : null;
        //dump($roles); // dump pour voir le rôle de l'utilisateur
        if (!in_array("ROLE_USER", $roles) || $userId != $insect->getId()) {
            throw new AccessDeniedException("Vous n'avez pas les droits pour modifier ce compte.");
        }

        //création du formulaire avec le formType créé en annexe pour avoir à réutiliser le formType de l'inscription
        $form = $this->createForm(InsectEditType::class, $insect);

        //hydratation de l'entité
        $form->handleRequest($request);

        // Si validation du formlaire affiché
        if ($form->isSubmitted() and $form->isValid()) {

            //persistance et flush de l'objet $insect
            $em = $this->getDoctrine()->getManager();
            $em->persist($insect);
            $em->flush();

            // redirection vers la route contact
            return $this->redirectToRoute("contact", ["id" => $insect->getId()]);
        }

        // on retourne la vue contact avec en plus des listes le formulaire de modification du compte de l'utilisateur
        return $this->render('default/contact.html.twig', [
            "userList" => $listUsers,
            "listAmis" => $insect->getAmiList(),
            "insectForm" => $form->createView()
        ]);
    }


    /**
     * @Route("/ajoutAmi/{id}" ,  name="ajoutAmi")
     * @param Insect $insect
     * @return Response
     */
    public function ajoutAmiAction(Insect $insect)
    {
        // on récupère l'utilisateur connecté et ajout en tant qu'ami de l'utilisateur sélectionné via l'id donné en paramètre
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        // sécurité, si on est connecté (role "ROLE_USER")
        if (in_array("ROLE_USER", $roles)) {
            // grâce à symfony, avec l'id de l'objet Insect je peux utiliser directement l'entité complète via $Insect ici
            $user->addAmi($insect);

            // persistance et flush de l'ajout
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // redirection vers la route contact
            return $this->redirectToRoute("contact",
                ["id" => $user->getId()]);
        } else {
            // sinon (pas connecté) on est redirigé vers la route homepage
            return $this->redirectToRoute("homepage");
        }
    }

    /**
     * @Route("/supprAmi/{id}" ,  name="supprAmi")
     * @param Request $request
     * @param Insect $insect
     * @return Response
     */
    public function supprAmiAction(Request $request, Insect $insect)
    {
        // on récupère l'utilisateur connecté et suppression de la liste des amis de l'utilisateur sélectionné via l'id donné en paramètre
        $user = $this->getUser();
        $roles = isset($user) ? $user->getRoles() : [];
        // sécurité, si on est connecté (role "ROLE_USER")
        if (in_array("ROLE_USER", $roles)) {
            // grâce à symfony, avec l'id de l'objet Insect je peux utiliser directement l'entité complète via $Insect ici
            $user->removeAmi($insect);
            // persistance et flush de l'ajout
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // redirection vers la route contact
            return $this->redirectToRoute("contact",
                ["id" => $user->getId()]);
        } else {
            // sinon (pas connecté) on est redirigé vers la route homepage
            return $this->redirectToRoute("homepage");
        }
    }
}
