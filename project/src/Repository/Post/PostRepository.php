<?php

namespace App\Repository\Post;

use App\Entity\Post\Post;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(
		ManagerRegistry $registry, 
		private PaginatorInterface $paginator
		)
    {
        parent::__construct($registry, Post::class);
    }

	/**
	 * Get published Posts
	 * 
	 * @param int page
	 * @return  PaginationInterface
	 */
	public function findPublished(int $page): PaginationInterface
	{
		$data = $this->createQueryBuilder('p')
			->where('p.state LIKE :state')
			->setParameter('state', '%STATE_PUBLISHED%')
			->orderBy('p.createdAt', 'DESC')
			->getQuery()
			->getResult();

		$posts = $this->paginator->paginate($data,$page,9);

		return $posts;
	}
}
