# Отчёт о лабораторной работе
#### *Разработка сервисов*

## Пользовательский интерфейс:

Единственная страница:
![](https://github.com/FedrovSergey/lab5/blob/main/pics/1.png)

## Пользовательский сценарий:
У зашедшего на страницу пользователя есть две опции: нажать на левую кнопку и заполнить массив случайными значениями или нажать на правую кнопку и отсортировать массив

## API сервера:
Страница **get.php** принимает POST-запросы с полем id и возвращает значение элемента массива с указанным id, а **set.php** принимает POST-запросы с полями id и val, ничего не возвращая, заменяет значение элемента массива с индексом id на val.

## Хореография   
После прогрузки DOM-элементов страницы, массив td[ ] циклически заполняется ссылками на элементы таблицы, параллельно выполняются запросы к **get.php** с последующим занесением результатов в таблицу как innerHTML элементов массива td[ ].

При нажатии кнопки **Заполнить массив** таблицы через элементы массива td[ ] заполняется случайными значениями, параллельно отправляются запросы к **set.php**, чтобы содержимое БД соответствовало отображаемому на экране

При нажатии на кнопку **Отсортировать** выполняется сортировка пузырьком, после чего элементы таблицы обновляются через цикл с элементами массива td[ ]

## Структура базы данных
БД состоит из одной таблицы arr с полями id и val.

## Пример HTTP запросов/ответов
![](https://github.com/FedrovSergey/lab5/blob/main/pics/2.png)

## Значимые фрагменты кода

Функция **get(id)**:
```
    async function get(id) {
        let data = new FormData;
        data.append('id', id);
        
        let val = await fetch(
            "http://localhost/Lab5/get.php",
            {
                method: "POST",
                body: data
            }
        )
        .then(res => res.text());
        
        return {id: id, val: val};
    };
```
Функция **get(id, val)**:
```
    async function set(id, val) {
        let data = new FormData;
        data.append('id', id);
        data.append('val', val);
        
        fetch(
            "http://localhost/Lab5/set.php",
            {
                method: "POST",
                body: data
            }
        )
        
        return {id: id, val: val};
    };
  ```  
Реализация сортировки:
```
    document.querySelector('#sort').addEventListener('click', async () => {
        let c = 1;
        while (c > 0) {
            c = await iteration(1, (await get(0))['val']);
        }
        
        for (i = 0; i<10; i++) {
            td[i].innerHTML = (await get(i))['val'];
        }
    });
```    
Как можно видеть, внутренний цикл сортировки пузырьком выведен в функцию **iteration(id, val)**.
Это рекурсивная функция, принимающая индекс обрабатываемого элемента и значение предыдущего. Её возвращаемое значение - количество замен, произведённое в массиве за один проход. Вот её код:
```
    async function iteration(id, a) {
        if (id < 10) {
            return get(id)
            .then(res => {
                b = res['val'];
                if (a>b) {
                    set(id-1, b);
                    return set(id, a)
                    .then( () => {
                        return iteration(id+1, a).then(res => res+1);
                    });
                } else {
                    return iteration(id+1, b).then(res => res);
                }
            });
        }
        return 0;
    }
  ```  
Все её действия сводятся к следующему: при необходимости поменять местами значения текущего и предыдущего элементов, вызвать саму себя для следующего элемента, получить результат этого вызова и вернуть результат своего выполнения. Рекурсия прерывается с помощью 
```
    if (id < 10) {
        ...
    }
 ```   
, что не даёт ей выйти за пределы десятиэлементного массива. 