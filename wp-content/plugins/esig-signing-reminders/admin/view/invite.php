<?php 

// To default a var, add it to an array
	$vars = array(
		'esig_logo', // will default $data['esig_logo']
		'esig_header_tagline', 
		'document_title',
		'document_checksum',
		'owner_first_name',
		'signer_name',
		'signer_email',
		'view_url',
		'assets_dir',
		
	);
	$this->default_vals($data, $vars);
  
?>


<div id=":zs" class="ii gt m1436f203bed358e3 adP adO">
  <div id=":zr" style="overflow: hidden;">
    <div class="adM"> </div>
    <div
style="background-color:#efefef;margin:0;padding:0;font-family:'HelveticaNeue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;width:100%;min-width:680px">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
          <tr style="border-collapse:collapse">
            <td style="font-family:'HelveticaNeue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;border-collapse:collapse"
              align="center" bgcolor="#efefef">
              <table border="0" cellpadding="20" cellspacing="0"
                width="640">
                <tbody>
                  <tr style="border-collapse:collapse">
                    <td style="font-family:'HelveticaNeue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;border-collapse:collapse"
                      align="left" width="640">
                      <div style="margin:0 0 20px 0">
                        <div style="text-align:left"> <a href="http://www.approveme.me/wp-digital-e-signature?rel=em_lg&amp;utm_campaign=send_signature_request&amp;utm_source=default&amp;utm_medium=email&amp;utm_content=with_intro"
                            style="text-decoration:none" target="_blank"><img
                              style="margin-top: 0px; margin-bottom:
                              0px; padding-top: 0px; padding-bottom:
                              0px; border-top: 0px
                              none; border-bottom: 0px none;
                              -moz-border-top-colors: none;
                              -moz-border-right-colors: none;
                              -moz-border-bottom-colors: none;
                              -moz-border-left-colors: none;
                              border-image: none; line-height: 100%;
                              outline: medium none; text-decoration:
                              none;" src="<?php echo $data['assets_dir']; ?>/images/logo.png" alt="WP E-Signature"
                              border="0" height="55" width="243"></a></div>
                        <p style="margin:5px 0 0 0;color:#666">Sign Legally Binding Documents using a WordPress website<br>
                        </p>
                      </div>
                      <table width="640">
                        <tbody>
                          <tr>
                            <td
                              style="background-color:#f7fafc;padding:8px
                              10px;border:1px solid
                              #ccc;color:#444;font-weight:bold;margin-bottom:10px;text-align:center"
                              bgcolor="#F7FAFC">
                              <?php echo $data['user_full_name']; ?> has requested your signature</td>
                          </tr>
                        </tbody>
                      </table>
                      <table width="640">
                        <tbody>
                          <tr>
                            <td
                              style="background-color:#ffffff;border:1px
                              solid #ccc;padding:40px 40px 30px 40px"
                              bgcolor="FFFFFF">
                              <h1 style="font-size:18px;margin:0 0 10px
                                0;font-weight:bold">Document Name: <?php echo $data['document_title']; ?>
                              Document ID:( <?php echo $data['document_checksum']; ?>) 
                              <p
                                style="line-height:1.4em;font-size:14px;margin:10px
                                0px"> <span style="color:#8c8c8c">From: <?php echo $data['user_full_name']; ?> (<a
                                    href="mailto:<?php echo $data['user_email']; ?>"
                                    target="_blank"><?php echo $data['user_email']; ?>
                              </a>)
                                </span> </p>
                              <hr
style="color:#cccccc;background-color:#cccccc;min-height:1px;border:none">
                              <p
                                style="line-height:1.4em;font-size:14px;margin:10px
                                0px">Hi <?php echo $data['recipient_name']; ?> ,<br>
                                <br>
                                  <?php echo $data['user_full_name']; ?> sent you a document that needs to be signed.<br>
                                <br>
                                Please add your signature to the
                                document below. - <a
href="mailto:<?php echo $data['user_email']; ?>?subject=RE%3A%20%7B<?php echo $data['document_title']; ?>%7D"
style="color:#368bc6;text-decoration:none" target="_blank">Reply<br>
                                </a></p>
                              <hr
style="color:#cccccc;background-color:#cccccc;min-height:1px;border:none">
                              <div style="margin:20px 0px 20px 0px">
                                <table>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <a style="display:inline-block;padding:9px 16px 9px 16px; font:12px/28px 'ProximaNova-Bold', Arial, Helvetica, sans-serif-webkit-body;text-transform: uppercase;letter-spacing: 1px;line-height: 29px;text-decoration:none;color:#f7fbfd; background:#0083c5" href="[[invite_url]]" target="_blank">
                                        Review &amp; Sign</a>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                              <hr
style="color:#cccccc;background-color:#cccccc;min-height:1px;border:none">
                              <p style="margin:10px 0px;font-size:14px;line-height:1.4em;color:#ff0000">
                              	Warning: do not forward this email to others or
                                else they will have access to your document (on your behalf).</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                      <table>
                        <tbody>
                          <tr>
                            <td
                              style="background-color:#ffffff;margin-top:10px;border:1px
                              solid #ccc;padding:40px 40px 30px 40px"
                              bgcolor="#FFFFFF">
                              <h1 style="font-size:18px;color: #9d9e9e;margin:0 0 10px 0;font-weight:bold">
                              What is WP E-Signature?</h1>
                              <p style="line-height:1.4em;font-size:14px;color: #9d9e9e;margin:10px
                                0px">WP E-Signature by Approve Me is the
                                fastest way to sign and send documents
                                using WordPress. Save a tree (and a
                                stamp).  Instead of printing, signing
                                and uploading your contract, the
                                document signing process is completed
                                using your WordPress website. You have
                                full control over your data - it never
                                leaves your server. <br>
                                <b>No monthly fees</b> - <b>Easy to use
                                  WordPress plugin.</b><a style="color:#368bc6;text-decoration:none" href="http://www.approveme.me/wp-digital-e-signature/?ref=1" target="_blank"> Learn more</a> </p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
      <table style="min-width:680px;background:#cccccc;border-top:1px
        solid #999999;border-bottom:1px solid #999999;padding:0 0 30px
        0" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tbody>
          <tr style="border-collapse:collapse">
            <td style="font-family:'Helvetica
Neue',Arial,Helvetica,sans-serif;font-size:14px;line-height:1.4em;border-collapse:collapse"
              align="center" bgcolor="#cccccc">
              <table style="margin-top:20px" border="0" cellpadding="20"
                cellspacing="0" width="640">
                <tbody>
                  <tr style="border-collapse:collapse">
                    <td style="padding: 16px 12px 0px 0px;vertical-align: top;font-family: 'Helvetica Neue',Arial,Helvetica,sans-serif;font-size: 12px;line-height: 1.5em;border-collapse: collapse;color: #555;"
                      align="left">This message was sent to you by
                      <?php echo $data['user_full_name']; ?> who is using the WP
                      E-Signature Document Signing WordPress plugin. If
                      you would rather not receive email from this
                      sender you may contact the sender with your
                      request. </td>
                    <td style="padding:0px 12px 0px
                      0px;vertical-align:top;font-family:'Helvetica
Neue',Arial,Helvetica,sans-serif;font-size:12px;line-height:1.4em;border-collapse:collapse;color:#555"
                      align="left"> <br>
                    </td>
                    <td style="padding:0px 0px 0px
                      0px;vertical-align:top;font-family:'Helvetica
Neue',Arial,Helvetica,sans-serif;font-size:12px;line-height:1.4em;border-collapse:collapse;color:#555"
                      align="left"> <a href="http://www.approveme.me/?ref=1" target="_blank"><img
src="<?php echo $data['assets_dir']; ?>/images/approveme-badge.png"
                              alt="WP E-Signature" border="0" style="margin-top: -8px;"
                              height="49" width="154"></a><br>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="adL"> </div>
    </div>
    <div class="adL"> </div>
  </div>
</div>

