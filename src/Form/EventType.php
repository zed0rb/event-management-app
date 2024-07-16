<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter event...',
                ],
                'label' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a Event name.']),
                ],
            ])
            ->add('date', DateTimeType::class, [
                'attr' => [
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outline-none',
                ],
                'label' => false,
                'required' => true,
                'widget' => 'single_text',
                'html5' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a date and time.']),
                    new Type(['type' => \DateTimeInterface::class]),
                    new GreaterThanOrEqual([
                        'value' => new \DateTime(),
                        'message' => 'Event date cannot be in the past.',
                    ]),
                ],
            ])
            ->add('registrationLimit', IntegerType::class, [
                'attr' => [
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Enter registration limit...'
                ],
                'label' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a registration limit.']),
                    new Type(['type' => 'integer', 'message' => 'Registration limit must be a number.']),
                    new GreaterThanOrEqual(['value' => 1, 'message' => 'Registration limit must be at least 1.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
