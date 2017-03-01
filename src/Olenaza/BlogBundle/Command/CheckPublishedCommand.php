<?php

namespace Olenaza\BlogBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CheckPublishedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('blog:publish-posts')
            ->setDescription('Changes status of new posts available for publication.')
            ->setHelp('This command allows you find new posts available for publication on current date and change their status to "published".');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $postsToPublish = $em->getRepository('OlenazaBlogBundle:Post')->createQueryBuilder('p')
            ->where('p.published = :published')
            ->andWhere('p.forPublication = :forPublication')
            ->andWhere('p.publishedOn <= :today')
            ->setParameter('published', false)
            ->setParameter('forPublication', true)
            ->setParameter('today', new \DateTime('today'))
            ->getQuery()
            ->getResult();

        if (empty($postsToPublish)) {
            $output->writeln('There are no new posts available for publication.');
        } else {
            $output->writeln('The following posts were published:');

            foreach ($postsToPublish as $postToPublish) {
                $postToPublish->setPublished(true);

                $postTitle = $postToPublish->getTitle();

                $output->writeln("$postTitle");
            }

            $em->flush();
        }
    }
}
