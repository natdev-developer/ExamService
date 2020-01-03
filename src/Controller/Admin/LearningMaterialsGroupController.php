<?php
/**
 * Created by PhpStorm.
 * User: Asus
 * Date: 23.11.2019
 * Time: 17:20
 */

namespace App\Controller\Admin;


use App\Entity\Admin\LearningMaterialsGroup;
use App\Form\Admin\LearningMaterialsGroupType;
use App\Repository\Admin\LearningMaterialRepository;
use App\Repository\Admin\LearningMaterialsGroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LearningMaterialsGroupController extends AbstractController
{
    /**
     * @Route("/learningMaterialsGroup", name="learningMaterialsGroup")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(LearningMaterialsGroup::class);
        $learningMaterialsGroup = new LearningMaterialsGroup([]);

        $form = $this->createForm(LearningMaterialsGroupType::class, $learningMaterialsGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data[0] = $request->request->get('name_of_group');

            $group = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $values = $group->getAllInformation();
            $repositoryExam = new LearningMaterialsGroupRepository();

            $repositoryExam->insert($values);

             return $this->redirectToRoute('learningMaterialsGroupList');
        }

        return $this->render('learningMaterialsGroupAdd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/learningMaterialsGroupList", name="learningMaterialsGroupList")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function learningMaterialsGroupListCreate() {
        $learningMaterialsGroupInformation= new LearningMaterialsGroupRepository();
        $id = $learningMaterialsGroupInformation -> getQuantity();
        if($id>0) {
            $info=true;
            for ($i = 0; $i < $id; $i++) {
                $learningMaterialsGroup = $learningMaterialsGroupInformation->getLearningMaterialsGroup($i);

                $tplArray[$i] = array(
                    'id' => $i,
                    'name_of_group' => $learningMaterialsGroup['name_of_group'],
                );
            }
        } else {
            $info = false;
            $tplArray = array(
                'id' => "",
                'name_of_group' => "",
            );
        }
        if( isset( $_SESSION['information'] ) && count( $_SESSION['information'] ) > 0  ) {
            $infoDelete = $_SESSION['information'];
        } else {
            $infoDelete = "";
        }
        $_SESSION['information'] = array();
        return $this->render( 'LearningMaterialsGroupList.html.twig', array (
            'data' => $tplArray,
            'information' => $info,
            'infoDelete' => $infoDelete
        ) );
    }
    /**
     * @param Request $request
     * @param LearningMaterialsGroup $learningMaterialsGroup

     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("editLearningMaterialsGroup/{id}", name="editLearningMaterialsGroup")
     */
    public function editExam(Request $request, LearningMaterialsGroup $learningMaterialsGroup)
    {
        $learningMaterialsGroupInformation = new LearningMaterialsGroupRepository();
        $groupId = (int)$request->attributes->get('id');
        $infos = $learningMaterialsGroupInformation->getLearningMaterialsGroup($groupId);

        $examInfoArray = array(
            'name_of_group' => $infos['name_of_group'],
        );

        $form = $this->createForm(LearningMaterialsGroupType::class, $learningMaterialsGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exams = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();


            $values = $learningMaterialsGroup->getAllInformation();

            $learningMaterialsGroupInformation->update($values,$groupId);
            return $this->redirectToRoute('learningMaterialsGroupList');
        }
        return $this->render('learningMaterialsGroupAdd.html.twig', [
            'form' => $form->createView(),
            'examInformation' =>$examInfoArray,
        ]);
    }


    /**
     * @param Request $request
     * @Route("/deleteGroup/{learningMaterialsGroup}", name="deleteGroup")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteGroup(Request $request)
    {
        $id = $request->attributes->get('learningMaterialsGroup');
        $repo = new LearningMaterialsGroupRepository();
        $learningMaterialRepo = new LearningMaterialRepository();
        $isMaterial = $learningMaterialRepo->getQuantity($id);
        if($isMaterial){
            $_SESSION['information'][] = array( 'type' => 'error', 'message' => 'The record cannot be deleted, there are links in the database');

        } else {
            $repo->delete($id);
            $_SESSION['information'][] = array( 'type' => 'ok', 'message' => 'Successfully deleted');

        }
        return $this->redirectToRoute('learningMaterialsGroupList');
    }
}