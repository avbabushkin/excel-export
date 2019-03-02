<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// подклюаем класс Xlsx
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// подключаем класс Spreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;

/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();

        return $this->render('user/index.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('AppBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    // экспорт to CSV
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository('AppBundle:User')->findAll();
        $writer = $this->container->get('egyg33k.csv.writer');
        $csv = $writer::createFromFileObject(new \SplTempFileObject());
        $csv->insertOne(['FIO', 'E-Mail']);

        foreach ($users as $user) {
            $csv->insertOne([$user->getName(), $user->getEmail()]);
        }

        $csv->output('users.csv');
        die();
    }

    // экспорт в XLSX
    // Экспорт данных в *.xslx возможно реализовать
    // при помощи библиотеки PhpSpreadsheet
    // я не до конца разобрался, как именно работать с ней
    // поэтому мое решение осталось незаконченным

    public function exportxlsAction()
    {
        // переменная для работы с Doctrine
        $em = $this->getDoctrine()->getManager();

        // хэш пользователей
        $users = $em->getRepository('AppBundle:User')->findAll();

        // Создаем новую таблицу
        $spreadsheet = new Spreadsheet();
        // сохраняем активный лист
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'FIO');
        $sheet->setCellValue('B1', 'E-Mail');

        // здесь по логике мы должны в цикле перебрать хэш user
        // и передать в лист $sheet
        foreach ($users as $user) {
            // TODO
            // $sheet->insertOne([$user->getName(), $user->getEmail()]);
        }

        // Определяем формат файла таблицы
        $writer = new Xlsx($spreadsheet);

        //запись в файл
        $writer->save('AwesomeExcel.xlsx');

        // Очищаем память
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }
}
