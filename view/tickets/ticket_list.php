    <h1>Elenco Ticket 2</h1>
    <a href="index.php?controller=Ticket&action=new">Crea nuovo ticket</a>
    <table>
        <tr><th>ID</th><th>Titolo</th><th>Stato</th><th>Azioni</th></tr>
        <?php foreach($tickets as $t): ?>
            <tr>
                <td><?= $t['ID'] ?></td>
                <td><?= htmlspecialchars($t['TITOLO']) ?></td>
                <td><?= $t['STATO'] ?></td>
                <td><a href="index.php?controller=Ticket&action=edit&id=<?= $t['ID'] ?>">Modifica</a></td>
            </tr>
        <?php endforeach; ?>
    </table>

