<script>
    
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
        .then(res => res.text())
        
        return {id: id, val: val};
    };

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

    function rand() {
        max = 9;
        min = 0;
        return Math.floor(Math.random() * (max - min + 1)) + min; //Максимум и минимум включаются
    }
    
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
    
</script>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
    <title></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</head>
<body>
    <div class="row justify-content-center mt-5">
        <div class="col-8 mt-1">
            <!-- Таблица -->
            <table class="table">
                <tr class="text-center">
                    <td id="e0"></td>
                    <td id="e1"></td>
                    <td id="e2"></td>
                    <td id="e3"></td>
                    <td id="e4"></td>
                    <td id="e5"></td>
                    <td id="e6"></td>
                    <td id="e7"></td>
                    <td id="e8"></td>
                    <td id="e9"></td>
                </tr>
            </table>
                <!-- Заполнение таблицы, получение массива элементов таблицы -->
            <script>
                    let td = [];
                    document.addEventListener("DOMContentLoaded", () => {
                        for (i = 0; i<10; i++) {
                            td[i] = document.querySelector(`#e${i}`);
                            get(i + 1)
                            .then( res => {
                                td[res['id']].innerHTML = res['val'];
                            });
                        };
                    });
            </script>
        </div>
    </div>
    
    
    <div class="row justify-content-center mt-2">
        <div class="col-3">
            <!-- Кнопка рандомизации массива -->
            <button id="shuffle" class="btn btn-lg btn-danger">Заполнить</button>
            <!-- Рандомизация массива -->
            <script>
                document.querySelector('#shuffle').addEventListener('click', () => {
                    for (i = 0; i<10; i++) {
                        set(i, rand())
                        .then(res => {
                            td[res['id']].innerHTML = res['val'];
                        });
                    };
                });
            </script>
            <!-- Кнопка для сортировки массива -->
            <button id="sort" class="btn btn-lg btn-primary float-end">Отсортировать</button>
            <!-- Сортировка массива по нажатию на кнопку -->
            <script>
                document.querySelector('#sort').addEventListener('click', async () => {
                    let c = 1;
                    while (c > 0) {
                        c = await iteration(1, (await get(0))['val']);
                    }
                    for (i = 0; i<10; i++) {
                        td[i].innerHTML = (await get(i))['val'];
                    }              
                });
            </script>
        </div>
    </div>
</body>