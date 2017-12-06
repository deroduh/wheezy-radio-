<a href="index.php?action=create">Create New</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post) : ?>
            <tr>
                <td><?php echo $post->id; ?></td>
                <td><?php echo $post->title; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($post->timecreated)); ?></td>
                <td>
                    <a href="index.php?action=create&id=<?php echo $post->id; ?>">EDIT</a>&nbsp;/
                    <a href="index.php?action=delete&id=<?php echo $post->id; ?>">DELETE</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<style>table {width: 100%;} td {border:1px solid #e7e7e7; padding: 5px;}</style>
