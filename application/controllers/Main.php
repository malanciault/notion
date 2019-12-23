<?php

use Illuminate\Support\Collection;
use Notion\NotionClient;
use Notion\Records\Blocks\CollectionRowBlock;
use Notion\Records\Blocks\PageBlock;

class Main extends MY_Controller
{
    private $client = false;

    public function __construct()
    {
        parent::__construct();
        $this->client = new NotionClient(getenv('MADEWITHLOVE_NOTION_TOKEN'));
    }

    public function index()
    {
        $this->get_all_todos();
    }

    public function get_all_todos() {



        $blogpostsPage = $this->client
            ->getBlock('https://www.notion.so/groupeamplio/e97ec0e3376246dfbf6c171fb05c8d77?v=13c0ca7169ce41a2a8b5e14030359f42')
            ->getCollection();
        $blogposts = $blogpostsPage->getRows()->sortByDesc(function (CollectionRowBlock $block) {
            return $block->created_time->format('Y-m-d');
        });

        foreach ($blogposts as $todo) {
            x($todo->id);
            x($todo->title);
            x($todo->statut);
        }
        xd(11);
        if ($title = $_POST['title'] ?? '') {
            $block = $todoPage->addRow(['title' => $title]);
        } elseif ($id = $_GET['mark_as_done'] ?? null) {
            $row = $todoPage->getRow($id);
            $row->done = true;
        }

        foreach ($todos as $todo) {
            x($todo->id);
            x($todo->title);
            x($todo->statut);
        }
    }

    public function get_filtered_todos() {
        $project = $this->client->getBlock('https://www.notion.so/groupeamplio/e97ec0e3376246dfbf6c171fb05c8d77?v=b0b5b539c44c48bb93d5c87809736b56');
        $pendingProposals = $project
            ->findChildByTitle('Pipedrive')
            ->findChildByTitle('Pipedrive')
            ->getRows()
            ->where('status', '=', 'Review');
    }
}