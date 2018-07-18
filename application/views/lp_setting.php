<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
    <div class="row" id="maincontents">
        <div class="col-md-2">
            <nav class="nav nav-pills flex-column">
            <?php if (isset($menues['link_item'])) : ?>
                <?php foreach ($menues['link_item'] as $item) : ?>
                    <a class="nav-link <?php if ($item['active'] == "active") : ?>active<?php endif; ?>" href="<?php echo $item['href']; ?>" <?php if (isset($item['target'])) : echo 'target="'.$item['target'].'"'; endif; ?>>
                        <?php echo $item['text']; ?>
                        <?php if ($item['active'] == "active") : ?><span class="sr-only">(current)</span><?php endif; ?></a>
                <?php endforeach; ?>
            <?php endif; ?>
            </nav>
        </div>
        <div class="col-md-5">
            <h2>サイトを選択</h2>
            <!-- 設定フォーム TODO: サイト別の仕組みを実装 -->
            <div class="list-group">
                <?php foreach ($sites as $site) : ?>
                    <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center <?php if (uri_string() == 'lp_setting/site/'.$site['id']) : ?>active<?php endif; ?>" href="<?php echo base_url('lp_setting/site/'.$site['id']); ?>"><?php echo $site['name']; ?>
                        <span class="badge badge-primary badge-pill"><?php echo $site['group_name']; ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-5">
            <!-- TODO: サイト選択されてからフォーム表示 -->
            <?php if ( ! empty($site_id)) : ?>
            <h2><?php echo $site_data['name']; ?></h2>
            <?php echo form_open('lp_setting/update', array('class' => 'form'), array('back' => 'lp_setting/site/'.$site_data['id'], 'site_id' => $site_data['id'])); ?>
            <div id="anti_pattern">
                <label>
                    除外パターン
                    <a href="#" class="badge badge-light" data-toggle="modal" data-target="#pattern_hint">
                        ？
                    </a>
                </label>
                <!-- Modal -->
                <div class="modal fade" id="pattern_hint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">除外パターン</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                例）<br>
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>設定</th>
                                        <th>一致するパターン</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <th>
                                            <span class="text-info">index</span>
                                        </th>
                                        <td>
                                            部分一致。indexを含むURLを除外します。<br>
                                            なお、indexで終わる場合も除外します。<br>
                                            http://www.domain.com/<span class="text-danger">index</span>.html<br>
                                            http://www.domain.com/sample/<span class="text-danger">index</span><br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <span class="text-info">http:*</span>
                                        </th>
                                        <td>
                                            前方一致。http:から始まるURL（SSLではないURL）を除外します。<br>
                                            <span class="text-danger">http:</span>//www.domain.com/<br>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            <span class="text-info">*jpg</span>
                                        </th>
                                        <td>
                                            後方一致。jpgで終わる画像ファイルへのURLを除外します。<br>
                                            http://www.domain.com/img/sample.<span class="text-danger">jpg</span><br>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br>
                                部分一致 … URLの一部に文字列を含む場合に除外します。<br>
                                前方一致 … 指定した文字列から始まるURLを除外します。<br>
                                後方一致 … 指定した文字列で終わるURLを除外します。
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php foreach ($patterns as $pattern) : ?>
                    <div class="form-group">
                        <input type="text" name="pattern[]" class="form-control" placeholder="除外パターン" value="<?php echo $pattern["value"]; ?>">
                    </div>
                <?php endforeach; ?>
                <div class="form-group">
                    <input type="text" name="pattern[]" class="form-control" placeholder="除外パターン">
                </div>
            </div>
            <div class="form-group">
                <a href="#" onclick="javascript:addpattern();">行を追加</a>
            </div>

                <!-- インデックスチェックを保存する月数 -->
                <div class="form-group">
                    <label>インデックス履歴を残す月数</label>
                    <div class="input-group">
                        <input type="number" name="indexmonth" class="form-control text-right" value="<?php echo (isset($indexmonth[0]['value'])) ? $indexmonth[0]['value'] : INDEXMONTH; ?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">ヶ月</span>
                        </div>
                    </div>
                </div>

                <!-- 優先度：高 -->
                <div class="form-group">
                    <label>
                        優先度：高
                        <a href="#" class="badge badge-light" data-toggle="modal" data-target="#priority_hint">
                            ？
                        </a>
                    </label>
                    <!-- Modal -->
                    <div class="modal fade" id="priority_hint" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">優先度の設定について</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    低品質の優先度を評価する基準を設定します。<br>
                                    基準に使用する項目にチェックを入れ、基準値を設定してください。<br>
                                    チェックを入れなかった項目は、判断基準としては無視します。<br>
                                    <h5>デフォルト設定</h5>
                                    <img src="<?php echo base_url('images/priority_setting.png'); ?>" class="float-right img-thumbnail" alt="デフォルト設定">
                                    <dl>
                                        <dt>優先度：高</dt>
                                        <dd>Googleキャッシュ日からの<span class="text-danger">40日</span>経過<br>
                                            <span class="text-muted">So-netインデックスチェックは使用しない</span></dd>
                                        <dt>優先度：中</dt>
                                        <dd><span class="text-muted">Googleキャッシュ日は使用しない</span><br>
                                            So-netインデックスチェックが<span class="text-danger">2ヶ月</span>連続なし</dd>
                                        <dt>優先度：低</dt>
                                        <dd><span class="text-muted">Googleキャッシュ日は使用しない</span><br>
                                            So-netインデックスチェックが<span class="text-danger">1ヶ月</span>なし</dd>
                                    </dl>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="google_cache_days_check_0" value="on" id="google_cache_days_check_0" <?php if (isset($google_cache_days_check[0]) && $google_cache_days_check[0] == "on") : echo "checked"; endif; ?>>
                        <label class="form-check-label" for="google_cache_days_check_0">
                            Googleキャッシュ日からの経過日数を使用
                        </label>
                    </div>
                    <div class="input-group">
                        <input type="number" name="google_cache_days_0" class="form-control text-right" value="<?php echo (isset($google_cache_days[0])) ? $google_cache_days[0] : GOOGLECACHEDAYS0; ?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">日経過</span>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="index_check_check_0" value="on" id="index_check_check_0" <?php if (isset($index_check_check[0]) && $index_check_check[0] == "on") : echo "checked"; endif; ?>>
                        <label class="form-check-label" for="index_check_check_0">
                            So-netインデックスチェックを使用
                        </label>
                    </div>
                    <div class="input-group">
                        <input type="number" name="index_check_0" class="form-control text-right" value="<?php echo (isset($index_check[0])) ? $index_check[0] : INDEXCHECK0; ?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">ヶ月無し</span>
                        </div>
                    </div>
                </div>

                <!-- 優先度：中 -->
                <div class="form-group">
                    <label>優先度：中</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="google_cache_days_check_1" value="on" id="google_cache_days_check_1" <?php if (isset($google_cache_days_check[1]) && $google_cache_days_check[1] == "on") : echo "checked"; endif; ?>>
                        <label class="form-check-label" for="google_cache_days_check_1">
                            Googleキャッシュ日からの経過日数を使用
                        </label>
                    </div>
                    <div class="input-group">
                        <input type="number" name="google_cache_days_1" class="form-control text-right" value="<?php echo (isset($google_cache_days[1])) ? $google_cache_days[1] : GOOGLECACHEDAYS1; ?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">日経過</span>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="index_check_check_1" value="on" id="index_check_check_1" <?php if (isset($index_check_check[1]) && $index_check_check[1] == "on") : echo "checked"; endif; ?>>
                        <label class="form-check-label" for="index_check_check_1">
                            So-netインデックスチェックを使用
                        </label>
                    </div>
                    <div class="input-group">
                        <input type="number" name="index_check_1" class="form-control text-right" value="<?php echo (isset($index_check[1])) ? $index_check[1] : INDEXCHECK1; ?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">ヶ月無し</span>
                        </div>
                    </div>
                </div>

                <!-- 優先度：低 -->
                <div class="form-group">
                    <label>優先度：低</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="google_cache_days_check_2" value="on" id="google_cache_days_check_2" <?php if (isset($google_cache_days_check[2]) && $google_cache_days_check[2] == "on") : echo "checked"; endif; ?>>
                        <label class="form-check-label" for="google_cache_days_check_2">
                            Googleキャッシュ日からの経過日数を使用
                        </label>
                    </div>
                    <div class="input-group">
                        <input type="number" name="google_cache_days_2" class="form-control text-right" value="<?php echo (isset($google_cache_days[2])) ? $google_cache_days[2] : GOOGLECACHEDAYS2; ?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">日経過</span>
                        </div>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="index_check_check_2" value="on" id="index_check_check_2" <?php if (isset($index_check_check[2]) && $index_check_check[2] == "on") : echo "checked"; endif; ?>>
                        <label class="form-check-label" for="index_check_check_2">
                            So-netインデックスチェックを使用
                        </label>
                    </div>
                    <div class="input-group">
                        <input type="number" name="index_check_2" class="form-control text-right" value="<?php echo (isset($index_check[2])) ? $index_check[2] : INDEXCHECK2; ?>" >
                        <div class="input-group-append">
                            <span class="input-group-text">ヶ月無し</span>
                        </div>
                    </div>
                </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary">設定を更新</button>
            </div>
            <?php echo form_close(); ?>
            <script type="text/javascript">
                // 除外パターンの入力欄を追加
                function addpattern() {
                    var d = document.getElementById('anti_pattern');
                    var addhtml = '<div class="form-group">';
                    addhtml += '<input type="text" name="pattern[]" class="form-control" placeholder="除外パターンを入力">';
                    addhtml += '</div>';
                    d.innerHTML += addhtml;
                }
            </script>
            <?php endif; ?>
        </div>
    </div>
    <div class="row" id="footer">

    </div>
