jQuery(function($) {
    var commentConverter = Markdown.getSanitizingConverter();
    commentEditor = new Markdown.Editor(commentConverter);
    commentEditor.run();

    var tagSubmitSuccess = false,
        tagForm = $('#tagForm'),
        tagModal = $('.modal', tagForm),
        tagName = $('#tag-name', tagForm),
        tagDescription = $('#tag-description', tagForm);

    tagForm.on('submit', function (e) {
        if (!tagForm.data('yiiActiveForm').validated) return; // 标签ajax 验证完后提交创建

        e.preventDefault();
        $.ajax({
            url: tagForm.attr('action'),
            type: 'POST',
            data: tagForm.serialize(),
            success: function(res) {
                if (res.type == 'success') {
                    tagSubmitSuccess = true;
                    tagModal.modal('hide');
                } else {
                    alert('标签创建失败');
                }
            }
        });

        return false;
    });

    var tagInput = $('#questionform-tags');
    tagInput.selectize({
        valueField: 'name',
        labelField: 'name',
        searchField: 'name',
        maxItems: tagInput.data('max-length'),
        plugins: ['remove_button'],
        create: function(input, create) {
            var _this = this;
            tagSubmitSuccess = false; //标记为未提交验证

            // 重置表单内容和状态
            tagName.val(input);
            tagDescription.val('');
            tagForm.yiiActiveForm('resetForm');
            tagForm.data('yiiActiveForm').validated = false;

            tagModal
                .modal('show')
                .one('hide.bs.modal', function() {
                    var data = {};
                    if (!tagSubmitSuccess) {
                        _this.removeOption(input);
                        data = false;
                    } else {
                        data[_this.settings.labelField] = input;
                        data[_this.settings.valueField] = input;
                    }
                    create(data);
                    setTimeout(function() {
                        _this.focus();
                    }, 1)
                });
        },
        createFilter: function(query) {
            return !this.options.hasOwnProperty(query);
        },
        render: {
            item: function(data, escape) {
                return '<div>"' + escape(data.name) + '"</div>';
            },
            option_create: function(data, escape) {
                return '<div class="create">创建标签 <strong>' + escape(data.input) + '</strong></div>';
            }
        },
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: G.getUrl('tag', 'search') + '?name=' + query,
                type: 'GET',
                error: function() {
                    callback();
                },
                success: function(res) {
                    if (res.type == 'success') {
                        callback(res.message);
                    } else {
                        callback();
                    }
                }
            });
        }
    });
});