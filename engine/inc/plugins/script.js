$(document).ready(function() {

            var button = $('#uploadButton'), interval;


            $.ajax_upload(button, {
                        action : '?mod=rss&action=upload',
                        name : 'uploadfile',
                        onSubmit : function(file, ext) {
                            // ���������� �������� �������� �����
                            $("#uploadButton").text('��������');

                            /*
                             * ��������� ������ �� ����� �������� �����
                             */
                            this.disable();

                        },
                        onComplete : function(file, response) {
                            // ������� �������� �������� �����
                            $("#uploadButton").text('���������');

                            // ����� �������� ������
                            this.enable();
                            // ���������� ��� ���� ��������
							$("#find_upload").text('');
                            $(response).appendTo("#find_upload");

                        }



                    });
        });
