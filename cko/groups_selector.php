<?php

echo '<form action="gen.php" method="post">
        <label for="group">Номер группы</label>
        <select id="group" name="group">
            <option>4</option>
            <option>5</option>
            <option>6</option>
            <option>7</option>
        </select>
        <label for="mod">Тип ведомости</label>
        <select id="mod" name="mod">
            <option>1</option>
            <option>2</option>
            <option>Итоговая ведомость</option>
        </select>
        
        <button type="submit">Создать ведомость</button>
      </form>';