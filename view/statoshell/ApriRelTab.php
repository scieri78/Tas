<?php if ($InIdSh != "" or $InIdRunSql != "") { ?>
  <br>
  <br>
  <table id='idTabella' class="display dataTable">
    <thead class="headerStyle">
      <tr>
        <?php if ($InIdSh != "") { ?>
          <th>PATH</th>
          <th>FILE</th>
        <?php } ?>
        <th>SCHEMA</th>
        <th>TABLE</th>
      </tr>
    </thead>
    <tbody>
      <?php

      foreach ($datiFileInfo as $row) {
        $RPath = $row['PATH'];
        $RFile = $row['FILE'];
        $RSchema = $row['SCHEMA'];
        $Rtabella = $row['TABELLA'];
      ?>
        <tr>
          <?php if ($InIdSh != "") { ?>
            <td><?php echo $RPath; ?></td>
            <td><?php echo $RFile; ?></td>
          <?php } ?>
          <td><?php echo $RSchema; ?></td>
          <td><?php echo $Rtabella; ?></td>
        </tr>
      <?php
      }
      foreach ($datiPackage as $row) {
        $PkgSchema = $row['PACKAGE_SCHEMA'];
        $PkgName = $row['PACKAGE'];
        $TabSchema = $row['TAB_SCHEMA'];
        $TabName = $row['TAB_NAME'];
      ?>
        <tr>
          <td><?php echo $PkgSchema; ?></td>
          <td><?php echo $PkgName; ?></td>
          <td><?php echo $TabSchema; ?></td>
          <td><?php echo $TabName; ?></td>
        </tr>
      <?php
      }
      ?>
    </tbody>
  </table>
<?php } ?>
<script src="./JS/datatable.config.js?p=<?php echo rand(1000,9999);?>"></script>