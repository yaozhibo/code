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
    main(CustomCountry, InsCountryZone);
    main(CustomCurr, InsCurrency);
    main(CustomCustom, InsCustom);
    main(CustomDistrict, InsDistrict);
    main(CustomPack, InsPackingType);
    main(CustomTrade, InsTransportType);
    //    main(CustomUnit, InsQuantityUnit);

    function main(arr1, arr2)
    {
        var reflectionList = [];
        var same = [];
        var diff_arr1 = [];
        var diff_arr2 = [];
        var foo = [];//middleware
        var i = 0;
        var j = 0;

        //1.匹配国家无歧义相同项生成映射数组
        $.each(arr1, function(index1, value1) {
            $.each(arr2, function(index2, value2) {
                if((slimString(value1[1])) === slimString(value2[1])) {
                    same[i] = value1[1];
                    reflectionList[i] = [value1, value2];
                    i = i+1;
                }
            });
        });

        //2.匹配报关国家有歧义项
        for(i=0; i<arr1.length; i++) {
            var flag = compare(same,slimString(arr1[i][1]));
            if(!flag) {
                diff_arr1[j] = arr1[i][1];
                j++;
            }
        }

        //匹配报检国家有歧义项
        for(i=j=0; i<arr2.length; i++) {
            var flag_ = compare(same, slimString(arr2[i][1]));
            if(!flag_) {
                diff_arr2[j] = arr2[i][1];
                j++;
            }
        }
//        showDiff(diff_arr1, diff_arr2);

        //3.合并有歧义相同项
        var fixed = fixSame_Metropolis(diff_arr1, diff_arr2);

        //4.将有歧义相同项添加至映射数组
        for(i=0; i<fixed.length; i++) {
            for(j=0; j<arr1.length; j++) {
                if(fixed[i][0] === arr1[j][1]) {
                    foo[i] = [arr1[j]];
                }
            }
            for(j=0; j<arr2.length; j++) {
                if(fixed[i][1] === arr2[j][1]) {
                    foo[i][1] = arr2[j]
                }
            }
        }

        var reflectionList_length = reflectionList.length;
        for(i=0; i<foo.length; i++) {
            reflectionList[reflectionList_length+i] = foo[i];
        }

        //5.匹配有歧义滞空项
        console.log(fixed);
        var temp_diff_arr1 = diff_arr1;
        var loop = temp_diff_arr1.length;
        for(i=0; i<loop; i++) {
            if(dimension2_compare(fixed, temp_diff_arr1[i])) {
                removeVal(temp_diff_arr1, temp_diff_arr1[i]);
                i = i-1;
                loop = loop -1;
            }
        }
        var ignore = [temp_diff_arr1];
        var temp_diff_arr2 = diff_arr2;
        loop = temp_diff_arr2.length;
        for(i=0; i<loop; i++) {
            if(dimension2_compare(fixed, temp_diff_arr2[i])) {
                removeVal(temp_diff_arr2, temp_diff_arr2[i]);
                i--;
                loop--;
            }
        }
        ignore[1] = temp_diff_arr2;
        /*console.log(ignore);
        console.log(reflectionList);//查看融合后的映射数组*/

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

    //模糊匹配
    function fixSame(arr1, arr2) {
        var k=0;
        var like = [];
//        var length = arr1.length < arr2.length? arr1.length : arr2.length;
        for(var i=0; i<arr1.length; i++) {
            var compare_1 = arr1[i].substring(0,2);
            var compare_4 = arr1[i].substring(0,1)+arr1[i].slice(-1);
            for(var j=0; j<arr2.length; j++) {
                var compare_2 = arr2[j].substring(0,2);
                var compare_3 = arr2[j].slice(-2);
                var compare_5 = arr2[j].substring(0,1)+arr2[j].slice(-1);
                if(compare_1 === compare_2) {
                    like[k] = [arr1[i],arr2[j]];
                    k++;
                } else if(compare_1 === compare_3) {
                    like[k] = [arr1[i],arr2[j]];
                    k++;
                } else if(compare_4 === compare_5) {
                    like[k] = [arr1[i], arr2[j]];
                    k++;
                }
                /*if(compare(sliceArray(like), arr1[i])) {
                        continue;
                    }*/
            }
        }
        return like;
    }

    //模拟退火
    function fixSame_Metropolis(arr1, arr2) {
        var countTimes = 2;
        var n = 0;
        var like = [];
        var T_start = new Date().getTime();
        for(var i=0; i<arr1.length; i++) {
            for(var count=1; count<=countTimes; count++) {
                for(var j=0; j<arr2.length; j++) {
                    var strLen1 = arr2[j].length;

                    var subchar = [];

                    for(var k=0; k<strLen1; k++) {
                        subchar[k] = arr2[j].substr(k, 1);
                    }

                    var l = 0;
                    for(var m=0; m<arr1[i].length; m++) {
                        if(compare(subchar, arr1[i].substr(m,1))) {
                            l++;
                        }
                    }
                    if(l>0) {
                        var percent = arr1[i].length/l;//取了倒数，此值越小越好
                        if(count === 1) {
                            var percent_old = percent;
                            var result = arr2[j];
                        } else {
                            if(percent < percent_old) {
                                percent_old = percent;
                                result = arr2[j];
                            } else {
                                var T = new Date().getTime()-T_start/1000;
                                if(Math.random()>Math.exp(-(percent- percent_old)/T)) {
                                    percent_old = percent;
                                    result = arr2[j];
                                }
                            }
                        }
                    }
                }
            }
            if(percent_old<2) { //设置50%匹配
                like[n] = [arr1[i], result];
                n++;
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