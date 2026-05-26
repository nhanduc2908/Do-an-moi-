import 'package:flutter/material.dart';

extension WidgetExtension on Widget {
  Widget paddingAll(double padding) {
    return Padding(padding: EdgeInsets.all(padding), child: this);
  }
  
  Widget paddingSymmetric({double horizontal = 0, double vertical = 0}) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: horizontal, vertical: vertical),
      child: this,
    );
  }
  
  Widget paddingOnly({
    double left = 0, double top = 0, double right = 0, double bottom = 0,
  }) {
    return Padding(
      padding: EdgeInsets.only(left: left, top: top, right: right, bottom: bottom),
      child: this,
    );
  }
  
  Widget center() => Center(child: this);
  
  Widget expanded({int flex = 1}) => Expanded(flex: flex, child: this);
  
  Widget sizedBox({double? width, double? height}) {
    return SizedBox(width: width, height: height, child: this);
  }
  
  Widget clipAll(double radius) {
    return ClipRRect(borderRadius: BorderRadius.circular(radius), child: this);
  }
  
  Widget clipOnly({double topLeft = 0, double topRight = 0, double bottomLeft = 0, double bottomRight = 0}) {
    return ClipRRect(
      borderRadius: BorderRadius.only(
        topLeft: Radius.circular(topLeft),
        topRight: Radius.circular(topRight),
        bottomLeft: Radius.circular(bottomLeft),
        bottomRight: Radius.circular(bottomRight),
      ),
      child: this,
    );
  }
  
  Widget marginAll(double margin) {
    return Container(margin: EdgeInsets.all(margin), child: this);
  }
  
  Widget marginSymmetric({double horizontal = 0, double vertical = 0}) {
    return Container(
      margin: EdgeInsets.symmetric(horizontal: horizontal, vertical: vertical),
      child: this,
    );
  }
  
  Widget background(Color color) {
    return Container(color: color, child: this);
  }
  
  Widget showIf(bool condition) {
    return condition ? this : const SizedBox.shrink();
  }
}