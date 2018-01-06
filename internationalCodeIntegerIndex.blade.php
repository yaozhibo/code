<html>
<head>
    <title>test</title>
    <meta charset="utf-8">
</head>
<body>
<script src="./jquery.min.js"></script>
<script src="./CustomBaseData.js"></script>
<script src="./InspectionBaseData.js"></script>
<script>
    //TODO:不要删除注释语句
    main_country();
    function main_country()
    {
        var reflectionList = [];
        var same = [];
        var diff_CustomCountry = [];
        var diff_InsCountryZone = [];
        var foo = [];//middleware
        var i = 0;
        var j = 0;

        //1.匹配国家无歧义相同项生成映射数组
        $.each(CustomCountry, function(index1, value1) {
            $.each(InsCountryZone, function(index2, value2) {
                if((slimString(value1[1])) == slimString(value2[1])) {
                    same[i] = value1[1];
                    reflectionList[i] = [value1, value2];
                    i = i+1;
                }
            });
        });

        //2.匹配报关国家有歧义项
        for(i=0; i<CustomCountry.length; i++) {
            var flag = compare(same,CustomCountry[i][1]);
            if(!flag) {
                diff_CustomCountry[j] = CustomCountry[i][1];
                j++;
            }
        }

        //匹配报检国家有歧义项
        for(i=j=0; i<InsCountryZone.length; i++) {
            var flag_ = compare(same, InsCountryZone[i][1]);
            if(!flag_) {
                diff_InsCountryZone[j] = InsCountryZone[i][1];
                j++;
            }
        }
//        showDiff(diff_CustomCountry, diff_InsCountryZone);

        //3.合并有歧义相同项
        var fixed = fixSame(diff_CustomCountry, diff_InsCountryZone);

        //4.将有歧义相同项添加至映射数组
        for(i=0; i<fixed.length; i++) {
            for(j=0; j<CustomCountry.length; j++) {
                if(fixed[i][0] === CustomCountry[j][1]) {
                    foo[i] = [CustomCountry[j]];
                }
            }
            for(j=0; j<InsCountryZone.length; j++) {
                if(fixed[i][1] === InsCountryZone[j][1]) {
                    foo[i][1] = InsCountryZone[j]
                }
            }
        }

        var reflectionList_length = reflectionList.length;
        for(i=0; i<foo.length; i++) {
            reflectionList[reflectionList_length+i] = foo[i];
        }

        //5.匹配有歧义滞空项
        console.log(fixed);
        var temp_diff_CustomCountry = diff_CustomCountry;
        var loop = temp_diff_CustomCountry.length;
        for(i=0; i<loop; i++) {
            if(dimension2_compare(fixed, temp_diff_CustomCountry[i])) {
                removeVal(temp_diff_CustomCountry, temp_diff_CustomCountry[i]);
                i = i-1;
                loop = loop -1;
            }
        }
        var ignore = [temp_diff_CustomCountry];
        var temp_diff_InsCountryZone = diff_InsCountryZone;
        loop = temp_diff_InsCountryZone.length;
        for(i=0; i<loop; i++) {
            if(dimension2_compare(fixed, temp_diff_InsCountryZone[i])) {
                removeVal(temp_diff_InsCountryZone, temp_diff_InsCountryZone[i]);
                i--;
                loop--;
            }
        }
        ignore[1] = temp_diff_InsCountryZone;
        console.log(ignore);
        console.log(reflectionList);//查看融合后的映射数组

    }

    //转化非必要字符
    function slimString(value) {
        var temp_value = value;
        temp_value = temp_value.replace(/\s/g, '');
        temp_value = temp_value.replace(/（/g, '(');
        temp_value = temp_value.replace(/）/g, ')');
        return temp_value;
    }

    //查看某值是否存在于一维数组
    function compare(arr, value) {
        var i =0;
        for(i; i<arr.length; i++) {
            if(arr[i] === value) {
                return true;
            }
        }
        return false;
    }

    //查看某值是否存在于二维数组
    function dimension2_compare (arr, value) {
        for(var i=0; i<arr.length; i++) {
            for(var j=0; j<arr[i].length; j++) {
                if(arr[i][j] === value) {
                    return true;
                }
            }
        }
        return false;
    }

    //展示数组
    function showArray(arr) {
        var i = 0;
        for(i;i<arr.length;i++) {
            console.log(i,arr[i]);
        }
    }

    //展示对比两个数组
    function showDiff(arr1, arr2) {
        var length = arr1.length > arr2.length? arr1.length : arr2.length;
        for(var i=0; i<length; i++) {
            console.log(i,arr1[i], '..........',arr2[i]);
        }
    }

    //截取二维数组某列生成新数组
    function sliceArray(arr, column) {
        var arr_temp = arr;
        var temp = [];
        for(var i=0; i<arr.length; i++) {
            temp[i] = arr_temp[i][column];
        }
        return temp;
    }

    //匹配拥有相同字符串的字符串
    function fixSame(arr1, arr2) {
        var k=0;
        var like = [];
        var length = arr1.length < arr2.length? arr1.length : arr2.length;
        for(var i=0; i<length; i++) {
            var compare_1 = arr1[i].substring(0,2);

            for(var j=0; j<length; j++) {
                var compare_2 = arr2[j].substring(0,2);
                var compare_3 = arr2[j].slice(-2);
                if(compare_1 === compare_2) {
                    /*if(compare(sliceArray(like), arr1[i])) {
                        continue;
                    }*/
                    like[k] = [arr1[i],arr2[j]];
                    k++;
                }
                if(compare_1 === compare_3) {
                    like[k] = [arr1[i],arr2[j]];
                    k++;
                }
            }
        }
        return like;
    }

    //返回数组中某值的index
    function indexOfVal(arr, val) {
        var loop = arr.length;
        for(var i=0; i<loop; i++) {
            if(arr[i] === val) {
                return i;
            }
        }
    }

    //删除数组中某值
    function removeVal(arr, val) {
        var index = indexOfVal(arr, val);
        if(index >= 0) {
            arr.splice(index, 1);
        }
    }

</script>
</body>
</html>