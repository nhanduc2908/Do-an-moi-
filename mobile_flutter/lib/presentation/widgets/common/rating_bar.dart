// Đường dẫn: mobile_flutter/lib/presentation/widgets/common/rating_bar.dart

import 'package:flutter/material.dart';

class RatingBar extends StatelessWidget {
  final double rating;
  final int maxRating;
  final double size;

  const RatingBar({
    super.key,
    required this.rating,
    this.maxRating = 5,
    this.size = 20,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: List.generate(maxRating, (index) {
        final starValue = index + 1;
        final isFullStar = rating >= starValue;
        final isHalfStar = rating >= starValue - 0.5 && rating < starValue;

        return Icon(
          isFullStar
              ? Icons.star
              : (isHalfStar ? Icons.star_half : Icons.star_border),
          size: size,
          color: Colors.amber,
        );
      }),
    );
  }
}