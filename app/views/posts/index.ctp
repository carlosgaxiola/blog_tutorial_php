<!-- Archivo: /app/views/posts/index.ctp -->

<h1>Blog posts</h1>
<?php echo $html->link('Add Post',array('controller' => 'posts', 'action' => 'add'))?>
<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Actions</th>
        <th>Created</th>
    </tr>

    <!-- Aqui se hace el ciclo que recorre nuestros arreglo $posts , imprimiendo la información de cada post-->
    <!-- version: <?php echo Configure::version() ?> -->
    <?php foreach ($posts as $post): ?>
        <tr>
            <td><?php echo $post['Post']['id']; ?></td>
            <td>
                <?php echo $html->link($post['Post']['title'], "/posts/view/".$post['Post']['id']); ?>
            </td>
            <td>
                <?php echo $html->link('Delete', array('action'=>'delete', 'id'=>$post['Post']['id']), null, 'Are you sure?')?>
                <?php echo $html->link('Edit', array('action'=>'edit', 'id'=>$post['Post']['id']));?>
            </td>
            <td><?php echo $post['Post']['created']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>