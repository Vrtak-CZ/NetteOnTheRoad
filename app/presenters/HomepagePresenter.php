<?php

/**
 * My Application
 *
 * @copyright  Copyright (c) 2010 John Doe
 * @package    MyApplication
 */

use Nette\Application\AppForm, 
	Nette\Forms\Form;

/**
 * Homepage presenter.
 *
 * @author     John Doe
 * @package    MyApplication
 */
class HomepagePresenter extends BasePresenter
{
	private $students = array("Jan", "Martin", "Petr", "<script>alert('ahoj');</script>");

	public function renderDefault()
	{
		if ($this->isAjax()) { // pokud je požadavek AJAXový
			$this->invalidateControl('content'); // překresly snippet control
		}
		
		$this->template->students = $this->students; // předání seznamu studentů šabloně
	}
	
	protected function createComponentForm()
	{
		$form = new AppForm;
		// vytvoříme políčko pro název předmětu
		$form->addText('predmet', "Předmět")
			->setRequired(); // nastavíme políčko jako povinné
		// vytvoříme políčko pro zadání známky
		$form->addText('znamka', "Znamka")
			->setAttribute('type', "numeric") // nastavím HTML 5 typ na číslo
			->setOption('description', \Nette\Web\Html::el('b')->setText("povolené 1-5")) // nastavíme nápovědu že čísla mohou být pouze od 1 do 5
			->addRule(Form::NUMERIC) // nastavíme validátor tak aby akceptoval pouze čísla
			->addRule(Form::RANGE, NULL, array(1, 5)); // nastavíme validátor tak aby akceptoval pouze čísla od 1 do 5
		
		$form->addSubmit('sub', "Přidej");
		
		$form->onSubmit[] = callback($this, ('processForm')); // nastavíme funkci pro zpracování formuláře
		
		return $form;
	}
	
	public function processForm($form)
	{
		$values = $form->values; // načteme si data z formuláře
		
		$this->flashMessage("Znamka za předmět: " . $values['predmet'] . " je " . $values['znamka']); // vypsání flas message že byla známka přidána
		
		$this->redirect("this"); // přesměrujeme na stejný view
	}
	
	public function renderDetail($student)
	{
		if ($this->isAjax()) { // pokud je požadavek ajaxový
			$this->invalidateControl('content'); // překresly snippet content
		}
		$this->template->student = $student; // předání jména studenta do šablony
	}

}
