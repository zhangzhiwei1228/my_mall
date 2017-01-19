<div class="scr-tit">
	<span>已保存 2 条地址</span>
</div>
<div class="web-list-table">
	<table>
		<tr>
			<td width="140">
				<span class="sp-sp fri-sp">
					<span>许少波</span>
					<span>浙江</span>
				</span>
			</td>
			<td>
				<span class="sp-sp sec-sp">
					<span>许少波</span>
				</span>
			</td>
			<td>
				<span class="sp-sp thr-sp">
					<span>浙江省杭州市莫干山路登云路口481弄中博文化创意园E座601</span>
				</span>
			</td>
			<td>
				<span class="sp-sp four-sp">
					<span>15967161500</span>
				</span>
			</td>
			<td width="50">
				<span class="sp-sp five-sp">
					<span>修改</span>
				</span>
			</td>
			<td width="50">
				<span class="sp-sp six-sp">
					<span>删除</span>
				</span>
			</td>
			<td width="90">
				<span class="sp-sp seve-sp">
					<span>默认地址</span>
				</span>
			</td>
		</tr>
		<tr>
			<td width="140">
				<span class="sp-sp fri-sp">
					<span>许少波</span>
					<span>浙江</span>
				</span>
			</td>
			<td>
				<span class="sp-sp sec-sp">
					<span>许少波</span>
				</span>
			</td>
			<td>
				<span class="sp-sp thr-sp">
					<span>浙江省杭州市莫干山路登云路口481弄中博文化创意园E座601</span>
				</span>
			</td>
			<td>
				<span class="sp-sp four-sp">
					<span>15967161500</span>
				</span>
			</td>
			<td>
				<span class="sp-sp five-sp">
					<span>修改</span>
				</span>
			</td>
			<td>
				<span class="sp-sp six-sp">
					<span>删除</span>
				</span>
			</td>
			<td>
				<span class="sp-sp seve-sp">
					<span>默认地址</span>
				</span>
			</td>
		</tr>
	</table>
</div>
<script>
	$(function(){
		$(".web-list-table table tr").eq(2).find('.fri-sp').addClass('cur');
		$(".web-list-table table tr").eq(2).find('.seve-sp').show();

		$(".web-list-table table td .six-sp").click(function(){
			$(this).parents("tr").remove();
		})

		$(".web-list-table table tr").click(function(){
			$(this).find('.seve-sp').css("display","block")
			$(this).siblings('tr').find('.seve-sp').hide();
			$(this).find('.fri-sp').addClass('cur').parents("tr").siblings('tr').find('.fri-sp').removeClass('cur');
		})
	})
</script>