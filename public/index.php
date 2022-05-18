<?php

/**
 * Application entry point
 *
 * @author Ernie Leseberg
 */

use Doctrine\DBAL\DriverManager;
use EL\Domain\Contact\Contact;
use EL\Domain\Contact\ContactId;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use EventSauce\MessageRepository\DoctrineMessageRepository\DoctrineUuidV4MessageRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Ramsey\Uuid\Uuid;
use Slim\Factory\AppFactory;

require __DIR__ . '/../slim/vendor/autoload.php';

$app = AppFactory::create();

$app->get('/create', function (Request $request, Response $response, $args) {
    $connectionParams = [
        'dbname' => 'db_es',
        'user' => 'db_es',
        'password' => 'db_es',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    ];

    // DBAL v3
    $connection = DriverManager::getConnection($connectionParams);

    $serialiser = new ConstructingMessageSerializer();
    $messageRepository = new DoctrineUuidV4MessageRepository(
        connection: $connection,
        tableName:  'events_contact',
        serializer: $serialiser,
    //tableSchema: new DefaultTableSchema(), // optional
    //uuidEncoder: new BinaryUuidEncoder(), // optional
    );

    $aggregateRootRepository = new EventSourcedAggregateRootRepository(
        'Contact',
        $messageRepository,
    // $messageDispatcher
    );

    $contactIdentifier = Uuid::uuid4();
    $contactId = ContactId::fromString($contactIdentifier);
    $contact = Contact::initiate($contactId);
    try {
        $aggregateRootRepository->persist($contact);
    } catch (Exception $e) {
        echo '<pre>';
        print_r($e);
    }

    $response->getBody()->write("Contact created with ID: $contactIdentifier");
    return $response;
});

$app->run();
