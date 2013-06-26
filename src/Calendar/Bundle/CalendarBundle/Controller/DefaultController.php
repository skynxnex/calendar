<?php

    namespace Calendar\Bundle\CalendarBundle\Controller;

    use Calendar\Bundle\CalendarBundle\Entity\Event;
    use Calendar\Bundle\CalendarBundle\Entity\User;
    use JMS\Serializer\SerializerBuilder;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Symfony\Component\Serializer\Encoder\XmlEncoder;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Serializer;

    class DefaultController extends Controller
    {

        public function indexAction()
        {
            return $this->render('CalendarCalendarBundle:Default:calendar.html.twig');
        }

        /**
         * @Route("/events")
         */
        public function eventsAction(Request $request)
        {
            $em = $this->getDoctrine()->getManager();
            $response = '';
            if ($request->getMethod() == 'GET') {

                $user = $em->getRepository('CalendarCalendarBundle:User')->findOneBy(array('userName' => 'tester'));
                if (!$user) {
                    $user = $this->createATestUser();
                }
                if($user->getEvents()->isEmpty()) {
                    $data = array(
                        array('id'     => 1,
                              'title'  => 'Test1',
                              'start'  => '2013-06-07 10:00:00',
                              'end'    => '2013-06-07 12:00:00',
                              'allDay' => false,
                              'eventColor' => '#FFF5EE'
                        ),
                        array('id' => 2, 'title' => 'Test2', 'start' => '2013-06-05', 'end' => '2013-06-06'),
                        array('id' => 50,
                              'title' => 'Test3',
                              'start' => date('D M d Y H:i:s O'),
                              'allDay' => false,
                              'backgroundColor' => '#FFF5EE',
                            'textColor' => 'black',
                            'resizeable' => false

                        )
                    );
                    $response = json_encode($data);
                } else {
                    $serializer = SerializerBuilder::create()->build();
                    $response = $serializer->serialize($user->getEvents(), 'json');
                }
            } else {
                if ($request->getMethod() == 'POST') {
                    $user = $em->getRepository('CalendarCalendarBundle:User')->findOneBy(array('userName' => 'tester'));
                    if (!$user) {
                        $user = $this->createATestUser();
                    }
                    $data = json_decode($request->getContent());
                    $event = new Event();
                    $event->setAllDay($data->allDay)
                    ->setTitle($data->title)
                    ->setStart($data->start)
                    ->setEnd($data->end)
                    ->setUrl('')
                    ->setUser($user);
                    $em->persist($event);
                    $em->flush();
//                    $serializer = SerializerBuilder::create()->build();
//                    $response = $serializer->serialize($event, 'json');
                    $response = json_encode(array('id' => $event->getId()));
                }
            }

            return new Response($response, 200, array('content-type' => 'application/json'));
        }

        /**
         * @param $id
         * @Route("/events/{id}")
         * @Method({"PUT"})
         * @return Response
         */
        public function saveEventAction($id)
        {
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('CalendarCalendarBundle:User')->findOneBy(array('userName' => 'tester'));
            $request = Request::createFromGlobals();
            $data = $request->getContent();
            $serializer = SerializerBuilder::create()->build();
            $deserialized = $serializer->deserialize($data, 'Calendar\Bundle\CalendarBundle\Entity\Event', 'json');
            $event = $em->merge($deserialized);
            $event->setUser($user);
            $em->persist($event);
            $em->flush();

            return new Response($data, 200);
        }

        private function createATestUser()
        {
            $em = $this->getDoctrine()->getManager();
            $user = new User();
            $user->setName('Test Person')
            ->setUserName('tester')
            ->setPassword(sha1('password'));
            $em->persist($user);
            $em->flush();

            return $user;
        }
    }
