package mapper

func Map[From any, To any](items []From, mapFn func(From) To) []To {
	result := make([]To, 0, len(items))
	for _, v := range items {
		result = append(result, mapFn(v))
	}
	return result
}
