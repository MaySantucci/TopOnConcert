<?php

namespace Application\Form\Concert;

use Application\Entity\Concert;
use Zend\Form\Form;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\Validator\StringLength;
use Zend\Validator\Date;
use Zend\Form\Element;

class ConcertForm extends Form
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * @var Concert
     */
    private $concert = null;

    public function __construct($entityManager = null, $concert = null)
    {
        parent::__construct('concert-form');

        $this->setAttribute('method', 'post');

        $this->entityManager = $entityManager;
        $this->concert = $concert;

        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements()
    {
        $this->add([
            'type' => Element\Text::class,
            'name' => 'artist',
            'options' => [
                'label' => 'Gruppo',
            ],
            'attributes' => [
                'placeholder' => 'Gruppo',
                'required' => true,
                'autofocus' => true,
                'value' => $this->concert ? $this->concert->getArtist() : '',
            ],
        ]);
        
        $this->add([
            'type' => Element\Date::class,
            'name' => 'date',
            'options' => [
                'label' => 'Data e ora',
                'format' => 'Y-m-d'
            ],
            'attributes' => [
                'placeholder' => 'Data e ora',
                'required' => true,
                'value' => $this->concert ? $this->concert->getDate() : '',
            ],
        ]);
        
        $this->add([
            'type' => Element\Number::class,
            'name' => 'price',
            'options' => [
                'label' => 'Prezzo'
            ],
            'attributes' => [
                'placeholder' => 'Prezzo',
                'required' => true,
                'value' => $this->concert ? $this->concert->getPrice() : '',
            ],
        ]);
        $this->add([
            'type' => Element\Text::class,
            'name' => 'location',
            'options' => [
                'label' => 'Luogo'
            ],
            'attributes' => [
                'placeholder' => 'Luogo',
                'required' => true,
                'value' => $this->concert ? $this->concert->getLocation() : '',
            ],
        ]);
        $this->add([
            'type' => Element\Number::class,
            'name' => 'availability',
            'options' => [
                'label' => 'Disponibilità'
            ],
            'attributes' => [
                'placeholder' => 'Disponibilità',
                'required' => true,
                'value' => $this->concert ? $this->concert->getAvailability() : '',
            ],
        ]);
        $this->add([
            'type' => Element\Hidden::class,
            'name' => 'id',
            'attributes' => [
                'value' => $this->concert ? $this->concert->getId() : '',
            ],
        ]);
        
        $this->add([
            'type' => Element\Submit::class,
            'name' => 'concert_submit',
            'attributes' => [
                'value' => 'Aggiungi'
            ],
        ]);
    }

    private function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);
        
        $inputFilter->add([
            'name' => 'artist',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        
        /*
        $inputFilter->add([
            'name' => 'date',
            'required' => true,
            'filters' => [
                ['name' => \Zend\Filter\DateTimeSelect::class],
            ],
        ]); 
         * 
         */
        
        $inputFilter->add([
            'name' => 'price',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
        
        $inputFilter->add([
            'name' => 'location',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
        
        $inputFilter->add([
            'name' => 'availability',
            'required' => true,
            'filters' => [
                ['name' => ToInt::class],
            ],
        ]);
        
        /*
        $inputFilter->add([
            'name' => 'organizer',
            'required' => true,
            'filters' => [
                ['name' => StripTags::class],
                ['name' => StringTrim::class],
            ],
            'validators' => [
                [
                    'name' => StringLength::class,
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
            ],
        ]);
         * 
         */
        

    }
}