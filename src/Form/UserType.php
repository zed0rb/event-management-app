<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter a name.']),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Name must be at least {{ limit }} characters long.',
                        'max' => 50,
                        'maxMessage' => 'Name cannot be longer than {{ limit }} characters.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Please enter an email.']),
                    new Email(['message' => 'Please enter a valid email address.']),
                    new Callback(function ($email, ExecutionContextInterface $context) use ($options) {
                        /** @var Event|null $event */
                        $event = $options['event'];

                        if (!$event) {
                            return;
                        }

                        // Check if the event date is in the past
                        if ($event->getDate() < new DateTime()) {
                            $context->buildViolation('Cannot register for an event that has already occurred.')
                                ->atPath('email')
                                ->addViolation();
                        }

                        // Check if user is already registered for this event
                        if ($event->getUsers()->count() > 0) {
                            foreach ($event->getUsers() as $user) {
                                if ($user->getEmail() === $email) {
                                    $context->buildViolation('You are already registered for this event.')
                                        ->atPath('email')
                                        ->addViolation();
                                    break;
                                }
                            }
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
