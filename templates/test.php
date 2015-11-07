<form class="form-horizontal" role="form">
  <div class="form-group">
    <label class="control-label col-sm-2" for="text">Link :</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="text" placeholder="Enter link">
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label><input type="checkbox" name="tag[]" value="1"> Java</label>
        <label><input type="checkbox" name="tag[]" value="2"> Python</label>
        <label><input type="checkbox" name="tag[]" value="3"> PHP</label>
      </div>
    </div>
  </div>
  <div class="form-group"> 
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">Submit</button>
    </div>
  </div>
</form>