# MediaBundle

The MediaBundle provides similar features that the nice SonataMediaBundle, but without the tight coupled
SonataAdminBundle parts and with some different OOP design approaches.

The MediaBundle provides a generic and universal way to handle different kinds of medias in a project.
It provides a bunch of services, each responsible for a specific task in the media journey.

It tries to abstract the filesystem through the nice and funny KnpLabs Gaufrette library, but also provides an abstraction
of CDN use.

The understanding of the bundle behaviour requires some bases knowledge in OOP design.
The understanding of the code itself, especially the configuration part requires strong skills with Symfony 2
and Dependency Injection system/concept.


## Installation

### Dependencies

#### AnoSystemBundle

Most of the Ano bundles have a dependency on a [AnoSystemBundle](https://github.com/benjamindulau/AnoSystemBundle)
which provides common behaviour such as DIC parameters remapping in extensions, or an inflector, or even an AclManager.

If you don't want the dependencies you can just c/c the `remapParametersNamespaces` and `remapParameters` from the
AnoSystemBundle Extension to the MediaExtension file and get rid of the AnoSystemBundle.

#### Gaufrette

The bundle has a dependency with the KnpLabs [Gaufrette](https://github.com/knplabs/Gaufrette) library, so you need to
install it in your projects and make sure to configure the autoloader for it.

#### Imagine

If you intend to use the default ImageManipulator implementation, you need to install the great
[Imagine](https://github.com/avalanche123/Imagine) library from Bulat Shakirzyanov.


### MediaBundle

The bundle goes to the `vendor/bundles/Ano/Bundle` directory (so you should have `vendor/bundles/Ano/Bundle/MediaBundle`)


## Concept

### Context/Provider concepts

The MediaBundle concept key is about `context`.
What is a `context` ?
A `context` is used to identify an use case for a Media in the application. It's needed by the MediaBundle to be able
to use the appropriate media provider for a specific media type (a video or an image for instance) and to know what
to do with it.

A `provider` is an object responsible for retrieving a specific media type, like a video for instance, and to perform the
operations relative to this media type. For example, an ImageProvider will know how to retrieve an image from filesystem and
how to generate thumbs (trought an image manipulator interface), while a VideoProvider will know how to retrieve metadata
from a WS API like Dailymotion or Youtube ones.

So the `provider` is determined from the `context`, and some configuration are given to it depending on the user needs
(several thumb sizes to be generated for instance). A bunch of tools are given to the `Provider` depending on the media type
 to be handled, like Gaufrette drivers for accessing the filesystem, or an Image manipulator implementation for
 generating thumbs, etc, etc.


## Implementation

So, enough about talking, let's take an example.

### Scenario

Say an user in our application can add an image as an avatar, and can eventually remove it.
The application is implemented under Symfony 2 and the user can interact through a standard Form.

Image files are stored in a `medias` folder inside the project structure, in the local filesystem, but are accessible
through a different host.

For display matters, the system needs two specific thumb sizes, `small: 50x50` and `medium: 90x90` in addition of the original file.

### Configuration

First, let's add some configuration according to our needs. We need to add the following in the `config.yml` file :

    ano_media:
        cdn:
            local:
                default: true
                id: ano_media.cdn.remote_server
                options:
                    base_url: "http://img.my.local" #(ideally, this parameter should lives in the parameters.yml file)

        provider:
            image:  # So we need the image provider here
                default: true
                id: ano_media.provider.image

        filesystem:
            local:
                default: true
                id: ano_media.filesystem.local
                options:
                    base_path: %kernel.root_dir%/../medias
                    create: true  #creates the directory it it doesn't exist yet

        contexts:
            user_picture:
                formats:
                    small: { width: 50, height: 50 }
                    medium: { width: 90, height: 90 }


That's it for the configuration, that's all we need.


### The Model

Now, we need to define our models to meet our needs, but before we do so, let's do some theory.

In a single application, there is often the need for different kinds of media objects : an avatar for the user, an image for
 an article or a news item, etc etc.

In the OOP world, this should be represented by "independent" class definitions, all sharing a common behaviour.
But as you certainly are wondering, how do we persist this ?

The response is: it doesn't matter, with the power of nowadays ORM, you should not be concerned about that.
Just create your models, and you'll take care of that later.

So, here we go :

First, we need a base Media class, which in most cases will not be used directly.

    namespace My\SiteBundle\Model;

    use Ano\Bundle\MediaBundle\Model\Media as BaseMedia;

    abstract class Media extends BaseMedia
    {

    }

Now, we need an object for each Media "use case". From an OOP point of view, an user picture (avatar) and other images for
 any other purposes should be represented by their own class, since their purposes in the system are different.

So, for our user picture we need a specific Media :

    namespace My\UserBundle\Model;

    use My\SiteBundle\Model\Media as BaseMedia;

    class UserPicture extends BaseMedia
    {
        protected $context = 'user_picture';
    }

Naturally, we need to define the relation with our User object (unidirectional here) :

    namespace My\UserBundle\Model;

    class User
    {
        private $name;
        private $picture;

        public function setPicture(UserPicture $picture)
        {
            $this->picture = $picture;
        }

        public function getPicture()
        {
            return $this->picture;
        }
    }

That's it for our model, nice and simple OOP.


### ORM Configuration

Now we defined our model, we need to create metadata for the ORM.
Here we'll use Doctrine 2 and standard relational database.

Like we said before, we could have several kinds of medias in a single application. But since my different media objects
 don't define specific data, we could persist them in a single shared database table. We'll use Doctrine SINGLE-TABLE INHERITANCE
 behaviour for this.

First of all, we need to add an identity to our Media base model, that's how we transform it into an entity (in the Doctrine world).
To be concise, i'll add the identifier directly inside the Media model class (but we could also create another namespace named `Entity`
 and create media subclasses here in order to isolate the Doctrine specifics).

    namespace My\SiteBundle\Model;

    use Ano\Bundle\MediaBundle\Model\Media as BaseMedia;

    abstract class Media extends BaseMedia
    {
        protected $id;

        public function setId($id)
        {
            $this->id = $id;
        }

        public function getId()
        {
            return $this->id;
        }
    }

And now, our mapping :

`My/SiteBundle/Resources/config/doctrine/Media.orm.xml` :

    <entity name="My\SiteBundle\Model\Media" table="medias" inheritance-type="SINGLE_TABLE">

        <id name="id" type="integer" column="id">
            <generator strategy="AUTO" />
        </id>

        <discriminator-column name="discr" type="string" />
        <discriminator-map>
            <discriminator-mapping value="media" class="Media" />
            <discriminator-mapping value="user_picture" class="My\UserBundle\Model\UserPicture" />
        </discriminator-map>
    </entity>

`My/UserBundle/Resources/config/doctrine/UserPicture.orm.xml` :

    <entity name="My\UserBundle\Model\UserPicture" />

`My/UserBundle/Resources/config/doctrine/User.orm.xml` :

    <entity name="My\UserBundle\Model\User" table="users">

        <id name="id" type="integer" column="id">
            <generator strategy="AUTO" />
        </id>

        <field name="name" column="name" type="string" length="30" />

        <one-to-one field="picture" target-entity="My\UserBundle\Model\UserPicture">
            <cascade>
                <cascade-all />
            </cascade>
        </one-to-one>

    </entity>

As you can see, i use the Doctrine operation cascading. That means that when you'll perform a persist operation
on an User instance ($em->persist($user)), Doctrine will cascade this operation to the UserPicture object living
 inside the User.

That is useful to avoid having to persist manually the UserPicture, which is not really logical.
The UserPicture is not living outside of an User instance, so from an OOP point of view, when we alter the user picture,
 we alter the user, and then, the user data needs to be saved (thus the user picture will be).

Naturally, that's exactly the same case on a remove operation.


### Frontend operation

The user needs to interact with the system in order to provide the picture he wants as his avatar.
We'll just use a simple Symfony Form for that. To be simple, i'll use a simple array as data for the form.

The idea is that we need the Form component to map the user uploaded file to a File object so we can easily get the binary
content of the file and set it into our UserPicture object.

`My/UserBundle/Form/UserProfileType` :

    namespace My\UserBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilder;

    class UserProfileType extends AbstractType
    {
        public function buildForm(FormBuilder $builder, array $options)
        {
            $builder
                ->add('picture', 'file')
            ;
        }

        public function getName()
        {
            return 'user_profile';
        }
    }

`Controller`:

    public function editPictureAction(Request $request)
    {
        $data = array('picture');
        $form = $this->formFactory->create(new UserProfileType(), $data);

        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);

            if ($form->isValid()) {
                $user = $this->getCurrentUser();

                if (!empty($data['picture'])) {
                    $userPicture = new UserPicture();
                    $userPicture->setName($data['picture']->getClientOriginalName());
                    $userPicture->setContent($data['picture']);
                    $user->setPicture($userPicture);

                    $this->userManager->saveUser($user);
                    $this->session->setFlash('notice', 'Avatar saved !');
                }

                return $this->getResponseRedirect('my_user_profile_edit');
            }
            else {
                $this->session->setFlash('errors', 'Validation errors, please fix your inputs');
            }
        }

        return $this->render('MyUserBundle:User:edit-profile', array(
            'form' => $form->createView(),
        ));
    }

And that's it, the MediaBundle will magically perform all the needed operations for an UserPicture.
If you take a look at your medias directory, you should see all the generated thumbs, and if you look at your database
 you'll see the media is correctly persisted :-)



