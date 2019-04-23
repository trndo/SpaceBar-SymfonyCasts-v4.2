<?php


namespace App\Form;

use App\Entity\Article;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {

        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,[
                'help' => 'Choose something catchy!',
            ])
            ->add('content')
            ->add('publishedAt',null ,[
                'widget' => 'single_text'
            ])
            ->add('author',EntityType::class,[
                'class' => User::class,
                'choice_label' => function(User $user){
                return sprintf('(%d)%s',$user->getId(),$user->getEmail());
                },
                'placeholder' => 'Choose an author',
                'choices' =>$this->repository
                ->findAllEmailAlphabetical(),
                'invalid_message' => 'Symfony is too smart for you hacking!'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Article::class
        ]);
    }


}