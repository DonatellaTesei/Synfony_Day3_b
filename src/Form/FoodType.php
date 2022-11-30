<?php

namespace App\Form;

use App\Entity\Food;
/* use Symfony\Component\Form\Extension\Core\Type\DateTimeType; */
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FoodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ["attr" => ["placeholder" => "please type the food name", "class" => "form-control mb-2"]])
            ->add('description', TextareaType::class, ["attr" => ["placeholder" => "please type the food description", "class" => "form-control mb-2", "id" => "name"]]) //id=>name is for styling
            ->add('price', IntegerType::class, ["attr" => ["placeholder" => "please type the food price", "class" => "form-control mb-2"]])
            ->add('allergenes', TextType::class, ["attr" => ["placeholder" => "please type the food allergenes", "class" => "form-control mb-2"]])
            ->add('picture', TextType::class, ["attr" => ["placeholder" => "please upload a picture", "class" => "form-control mb-2"]])
            ->add('Create', SubmitType::class, ["attr" => ["class" => "btn btn-primary"]]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Food::class
        ]);
    }
}
