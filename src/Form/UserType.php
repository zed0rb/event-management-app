<?php

namespace App\Form;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a name.']),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter email.']),
                    new Callback(function ($email, ExecutionContextInterface $context) use ($options) {
                        /** @var User|null $user */
                        $user = $this->entityManager->getRepository(User::class)->findOneBy([
                            'email' => $email,
                            'event' => $options['event'],
                        ]);

                        if ($user instanceof User) {
                            $context->buildViolation('You are already registered for this event.')
                                ->atPath('email')
                                ->addViolation();
                        }
                    }),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'event' => null,
        ]);

        $resolver->setRequired('event');
    }
}
