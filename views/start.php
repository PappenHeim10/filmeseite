<?php
// views/start.php
namespace mvc; // Add namespace here

if (!empty($movies) && is_array($movies) && isset($movies['Search'])) : ?>

    <div class="movie2">
        <?php foreach ($movies['Search'] as $movie) : ?>

            <div class="movie-item">
                <h3><a href="?view=singleMovies&imdbID=<?php echo rawurlencode($movie['imdbID']); ?>"><?php echo htmlspecialchars($movie['Title']); ?></a></h3>
                <p>Erscheinungsjahr: <?php echo htmlspecialchars($movie['Year']); ?></p>
                <?php if (!empty($movie['Poster']) && $movie['Poster'] !== 'N/A') : ?>
                    <img src="<?php echo htmlspecialchars($movie['Poster']); ?>" alt="<?php echo htmlspecialchars($movie['Title']); ?>">
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="pagination">
        <?php
        $totalResults = isset($movies['totalResults']) ? (int)$movies['totalResults'] : 0;
        $totalPages = ceil($totalResults / 10); // Assuming 10 movies per page

        if ($page > 1) : ?>
            <a href="?view=start&title=<?php echo rawurlencode($title); ?>&page=<?php echo ($page - 1); ?>">Vorherige Seite</a>
        <?php endif;
        if ($page < $totalPages) : ?>
            <a href="?view=start&title=<?php echo rawurlencode($title); ?>&page=<?php echo ($page + 1); ?>">Nächste Seite</a>
        <?php endif; ?>
        Seite <?php echo $page; ?> von <?php echo $totalPages; ?>
    </div>

<?php else : ?>
    <p>Keine Filme gefunden.</p>
<?php endif; ?>