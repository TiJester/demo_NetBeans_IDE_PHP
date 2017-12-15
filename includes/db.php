<?php

    class WishDB extends mysqli 
    {
        // единый экземпляр self, общий для всех экземпляров
        private static $instance = null;

        // конфигурация соедирения с базой данных
        private $user = "root";//пользователь к базе данных
        private $pass = ""; //пароль к базе данных
        private $dbName = "wishlist";
        private $dbHost = "localhost";
        
    // Этот метод должен быть статическим и должен возвращать экземпляр объекта, если объект еще не существует.
    public static function getInstance() 
        { 
        if (!self::$instance instanceof self) { self::$instance = new self; } 
        return self::$instance; 
        } 
    // Методы клонирования и пробуждения предотвращают внешнюю копию экземпляров класса Singleton, 
    // исключая, таким образом, возможность дублирования объектов. 
    public function __clone() 
        {
        trigger_error('Клонировать нельзя.', E_USER_ERROR); 
        } 
        public function __wakeup() 
        {
        trigger_error('Дезериализация не допускается.', E_USER_ERROR); 
        }
    
    // private конструктор
    private function __construct() 
        {
        parent::__construct($this->dbHost, $this->user, $this->pass, $this->dbName);
        if (mysqli_connect_error()) 
            {
            exit('Ошибка подключения (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
            }
        parent::set_charset('utf-8');
        }
    
    //Функции в классе WishDB    
    //get_wisher_id_by_name для получения идентификатора владельца на основе имени продавца
    //get_wishes_by_wisher_id для получения списка пожеланий желающего с определенным идентификатором
    //create_wisher для добавления новой записи wisher к пользователям таблицы

    //Функция требует имя воиска в качестве входного параметра и возвращает идентификатор wisher.
    public function get_wisher_id_by_name($name) 
       {
        $name = $this->real_escape_string($name);
        $wisher = $this->query("SELECT id FROM wishers WHERE name = '"
        . $name . "'"); if ($wisher->num_rows > 0)
            {
            $row = $wisher->fetch_row();
            return $row[0];
            } else
        return null; 
        }
        
    //Функция требует идентификатора идентификатора в качестве входного параметра и возвращает пожелания, зарегистрированные для пользователя.
    public function get_wishes_by_wisher_id($wisherID) 
        {
        return $this->query("SELECT id, description, due_date FROM wishes WHERE wisher_id=" . $wisherID);
        }  
        
    //Функция создает новую запись в таблице wishers. Функция требует имя и пароль нового wisher в качестве входных параметров и не возвращает никаких данных.
    public function create_wisher ($name, $password)
        {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $this->query("INSERT INTO wishers (name, password) VALUES ('" . $name . "', '" . $password . "')");
        }    

    // Чтобы выполнить проверку учетных данных wisher, вам нужно добавить новую функцию в класс WishDB в файле db.php. 
    // Функция требует имя и пароль в качестве входных параметров и возвращает 0 или 1.
    public function verify_wisher_credentials ($name, $password)
        {
        $name = $this->real_escape_string($name);
        $password = $this->real_escape_string($password);
        $result = $this->query("SELECT 1 FROM wishers WHERE name = '" . $name . "' AND password = '" . $password . "'"); return $result->data_seek(0); 
        }
    
    // Эта функция требует идентификатора wisher, описания нового желания и даты выполнения желания в качестве входных параметров и вводит эти данные в базу данных в новой записи. 
    // Функция не возвращает никаких значений.
    function insert_wish($wisherID, $description, $duedate)
        {
        $description = $this->real_escape_string($description);
        if ($this->format_date_for_sql($duedate)==null)
            {
            $this->query("INSERT INTO wishes (wisher_id, description)" ." VALUES (" . $wisherID . ", '" . $description . "')");
            } else
        $this->query("INSERT INTO wishes (wisher_id, description, due_date)" ." VALUES (" . $wisherID . ", '" . $description . "', ". $this->format_date_for_sql($duedate) . ")");
        }
    
    //преобразования формата данных даты    
    function format_date_for_sql($date)
        {
        if ($date == "")
            return null;
        else {
            $dateParts = date_parse($date);
            return $dateParts["year"]*10000 + $dateParts["month"]*100 + $dateParts["day"];
            }
        }
        
    //обновление желаний
    public function update_wish($wishID, $description, $duedate){ $description = $this->real_escape_string($description);
        if ($duedate==''){
        $this->query("UPDATE wishes SET description = '" . $description . "',
        due_date = NULL WHERE id = " . $wishID);
        } else
        $this->query("UPDATE wishes SET description = '" . $description .
        "', due_date = " . $this->format_date_for_sql($duedate)
        . " WHERE id = " . $wishID);
}
        
    }